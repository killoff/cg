<?php
class Cg_Kernel_Helper_Thumbnail
{
    protected $_width;
    protected $_height;

    protected $_source;
    protected $_destination;
    protected $_name;

    protected $_baseUrl;
    protected $_baseDir;
    protected $_createFolders = true;
    protected $_backgroundColor = null;


    public function __construct($source = null)
    {
        $this->_source = $this->_prepareSource($source);
        $this->_baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $this->_baseDir = Mage::getConfig()->getOptions()->getBaseDir();
    }

    public function getPath($width, $height)
    {
        $this->_reset();
        $this->_width = $width;
        $this->_height = $height;
        if (!$this->_exists()) {
            $this->_createThumbnail();
        }
        return $this->_getPath();
    }

    public function getUrl($width, $height)
    {
        $this->_reset();
        $this->_width = $width;
        $this->_height = $height;
        if (!$this->_exists()) {
            $this->_createThumbnail();
        }
        return $this->_getUrl();
    }

    public function setSource($source)
    {
        $this->_source = $this->_prepareSource($source);
        return $this;
    }

    public function setDestination($destination)
    {
        $this->_destination = $destination;
        return $this;
    }

    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    // reset object state to provide singleton behaviour
    protected function _reset()
    {
        $this->_name = null;
        $this->_destination = null;
    }

    protected function _getDestination()
    {
        if ($this->_destination === null) {
            $this->_destination = dirname($this->_source) . DS . $this->_getThumbnailDirName();
        }
        return $this->_destination;
    }

    protected function _getName()
    {
        if ($this->_name === null) {
            $this->_name = pathinfo($this->_source, PATHINFO_BASENAME);
        }
        return $this->_name;
    }

    protected function _exists()
    {
        return is_file($this->_getPath());
    }

    protected function _getPath()
    {
        return $this->_getDestination() . DS . $this->_getName();
    }

    protected function _getUrl()
    {
        // convert absolute path to URL if possible
        if (strpos($this->_getPath(), $this->_baseDir) === 0) {
            $relative = str_replace($this->_baseDir, '', $this->_getPath());
            $relative = str_replace(DS, '/', $relative);
            $relative = ltrim($relative, '/');
            return $this->_baseUrl . $relative;
        }
        return false;
    }

    protected function _createThumbnail()
    {
        require_once 'lib/cg/PHPImageWorkshop/ImageWorkshop.php';
        $layer = PHPImageWorkshop\ImageWorkshop::initFromPath($this->_source);
        $layer->resizeInPixel($this->_width, $this->_height);
        $layer->save($this->_getDestination(), $this->_getName(), $this->_createFolders, $this->_backgroundColor, 90);
    }

    protected function _getThumbnailDirName()
    {
        return $this->_width.'_'.$this->_height;
    }

    protected function _prepareSource($source)
    {
        $extension = pathinfo($source, PATHINFO_EXTENSION);
        $isBmp = strtolower($extension) === 'bmp';
        if ($isBmp) {
            try {
                $destination = substr($source, 0, strlen($source) - 3) . 'jpg';
                $this->_saveBmpAsJpeg($source, $destination);
            } catch (Exception $e) {
                return $source;
            }
            return $destination;
        }
        return $source;
    }

    protected function _saveBmpAsJpeg($filename, $destination)
    {
        // version 1.00
        if (!($fh = fopen($filename, 'rb'))) {
            throw new Exception('imagecreatefrombmp: Can not open ' . $filename);
        }
        // read file header
        $meta = unpack('vtype/Vfilesize/Vreserved/Voffset', fread($fh, 14));
        // check for bitmap
        if ($meta['type'] != 19778) {
            throw new Exception('imagecreatefrombmp: ' . $filename . ' is not a bitmap!');
        }
        // read image header
        $meta += unpack('Vheadersize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vcolors/Vimportant', fread($fh, 40));
        // read additional 16bit header
        if ($meta['bits'] == 16) {
            $meta += unpack('VrMask/VgMask/VbMask', fread($fh, 12));
        }
        // set bytes and padding
        $meta['bytes'] = $meta['bits'] / 8;
        $meta['decal'] = 4 - (4 * (($meta['width'] * $meta['bytes'] / 4)- floor($meta['width'] * $meta['bytes'] / 4)));
        if ($meta['decal'] == 4) {
            $meta['decal'] = 0;
        }
        // obtain imagesize
        if ($meta['imagesize'] < 1) {
            $meta['imagesize'] = $meta['filesize'] - $meta['offset'];
            // in rare cases filesize is equal to offset so we need to read physical size
            if ($meta['imagesize'] < 1) {
                $meta['imagesize'] = @filesize($filename) - $meta['offset'];
                if ($meta['imagesize'] < 1) {
                    throw new Exception('imagecreatefrombmp: Can not obtain filesize of ' . $filename . '!');
                }
            }
        }
        // calculate colors
        $meta['colors'] = !$meta['colors'] ? pow(2, $meta['bits']) : $meta['colors'];
        // read color palette
        $palette = array();
        if ($meta['bits'] < 16) {
            $palette = unpack('l' . $meta['colors'], fread($fh, $meta['colors'] * 4));
            // in rare cases the color value is signed
            if ($palette[1] < 0) {
                foreach ($palette as $i => $color) {
                    $palette[$i] = $color + 16777216;
                }
            }
        }
        // create gd image
        $im = imagecreatetruecolor($meta['width'], $meta['height']);
        $data = fread($fh, $meta['imagesize']);
        $p = 0;
        $vide = chr(0);
        $y = $meta['height'] - 1;
        $error = 'imagecreatefrombmp: ' . $filename . ' has not enough data!';
        // loop through the image data beginning with the lower left corner
        while ($y >= 0) {
            $x = 0;
            while ($x < $meta['width']) {
                switch ($meta['bits']) {
                    case 32:
                    case 24:
                        if (!($part = substr($data, $p, 3))) {
                            throw new Exception($error);
                        }
                        $color = unpack('V', $part . $vide);
                        break;
                    case 16:
                        if (!($part = substr($data, $p, 2))) {
                            throw new Exception($error);
                        }
                        $color = unpack('v', $part);
                        $color[1] = (($color[1] & 0xf800) >> 8) * 65536
                            + (($color[1] & 0x07e0) >> 3) * 256 + (($color[1] & 0x001f) << 3);
                        break;
                    case 8:
                        $color = unpack('n', $vide . substr($data, $p, 1));
                        $color[1] = $palette[ $color[1] + 1 ];
                        break;
                    case 4:
                        $color = unpack('n', $vide . substr($data, floor($p), 1));
                        $color[1] = ($p * 2) % 2 == 0 ? $color[1] >> 4 : $color[1] & 0x0F;
                        $color[1] = $palette[ $color[1] + 1 ];
                        break;
                    case 1:
                        $color = unpack('n', $vide . substr($data, floor($p), 1));
                        switch (($p * 8) % 8) {
                            case 0:
                                $color[1] = $color[1] >> 7;
                                break;
                            case 1:
                                $color[1] = ($color[1] & 0x40) >> 6;
                                break;
                            case 2:
                                $color[1] = ($color[1] & 0x20) >> 5;
                                break;
                            case 3:
                                $color[1] = ($color[1] & 0x10) >> 4;
                                break;
                            case 4:
                                $color[1] = ($color[1] & 0x8) >> 3;
                                break;
                            case 5:
                                $color[1] = ($color[1] & 0x4) >> 2;
                                break;
                            case 6:
                                $color[1] = ($color[1] & 0x2) >> 1;
                                break;
                            case 7:
                                $color[1] = ($color[1] & 0x1);
                                break;
                        }
                        $color[1] = $palette[ $color[1] + 1 ];
                        break;
                    default:
                        throw new Exception($filename . ' has ' . $meta['bits'] . ' bits and this is not supported!');
                }
                imagesetpixel($im, $x, $y, $color[1]);
                $x++;
                $p += $meta['bytes'];
            }
            $y--;
            $p += $meta['decal'];
        }
        fclose($fh);
        imagejpeg($im, $destination);
    }

}
