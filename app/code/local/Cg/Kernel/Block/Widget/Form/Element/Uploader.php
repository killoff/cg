<?php
class Cg_Kernel_Block_Widget_Form_Element_Uploader extends Varien_Data_Form_Element_Abstract
{
    public function getImagesHtml()
    {
        $html = '';
        if (is_array($this->getData('value'))) {
            foreach ($this->getData('value') as $file) {
                $html .= $this->_getImageHtml($file['url'],$this->getData('delete_url').'?'.http_build_query(array('file' => $file['name'])));
            }
        }
        return $html.'<div style="clear:both;padding-bottom:20px;"/>';
    }

    protected function _getImageHtml($url, $deleteUrl)
    {
        $html = '<div style="float: left;padding:10px;"><img style="height:100px;" src="%s"><br><a href="%s" onclick="return confirm(\'Удалить изображени?\')">удалить</a></div>';
        return sprintf($html, $url, $deleteUrl);
    }

    public function getElementHtml()
    {
        return $this->getImagesHtml() . '
            <button id="selectButton">Выбрать файлы...</button>
            <ul id="manualUploadModeExample" class="unstyled"></ul>
            <button id="triggerUpload">Загрузить!</button>

            <!--div class="">

            <div style="float: left;padding: 5px;"><img src="" alt="" style="width: 200px;"></div>
            <div style="float: left;padding: 5px;"><img src="" alt="" style="width: 200px;"></div>

            </div-->

    <script type="text/javascript">
    $j(document).ready(function() {
    var errorHandler = function(event, id, fileName, reason) {
        qq.log("id: " + id + ", fileName: " + fileName + ", reason: " + reason);
    };


    var fileNum = 0;
    var uploaded = 0;
    var completed = 0;
    $j("#manualUploadModeExample").fineUploader({
        autoUpload: false,
        uploadButtonText: "sdfsdf",
        request: {
        endpoint: "'.$this->getData('server_url').'"
        },
        display: {
        fileSizeOnSubmit: true
        },
        button: $j("#selectButton"),
        validation: {
        allowedExtensions: ["jpeg", "jpg", "png", "bmp"],
            sizeLimit: 10000000,
            minSizeLimit: 20000
        },
        drop: function (e, data) {
        console.log("dropped");
        },
        paste: function (e, data) {
        console.log("pasted");
        },

        success: function(response) {
            console.log(response);
        },

        add: function (e, data) {
        },
        change: function (e, data) {
        console.log("changed");
        }
    })
        .on("error", errorHandler)
        .on("fileuploadadd", function(e, data) {
            console.log("Added:");
        })
        .on("upload", function(e, data) {
            console.log("uploaded:");
            console.log(data);
        })
        .on("done", function(e, data) {
            console.log("done:");
            console.log(data);
        })
        .on("complete", function(event, id, name, responseJSON) {
            var file = responseJSON.file;
            if (file.error == 0) {
            $j("<input/>").attr("type", "hidden").attr("name", "files[]").val(JSON.stringify(file))
                .appendTo($j("#edit_form"));
            }
        });


        $j("#triggerUpload").click(function() {
            $j("#manualUploadModeExample").fineUploader("uploadStoredFiles");
            return false;
        });

//        $j("#manualUploadModeExample")
    });
    </script>

    ';
        return parent::getElementHtml();
    }
}
