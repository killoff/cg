<?php
class Cg_Register_Model_Generator extends Varien_Object
{
    /**
     * @const int
     */
    const TIME_STEP = 900; // 15 minutes

    /**
     * @var array
     */
    protected $_periods = array();

    /**
     * @var Cg_Kernel_Helper_Date
     */
    protected $_dateHelper = null;

    /**
     * @param array $periods
     */
    public function __construct(array $periods = array())
    {
        $this->setPeriods($periods);
        //$this->_dateHelper = Mage::helper('cg_kernel/date');
    }

    public function setPeriods(array $periods)
    {
        foreach ($periods as $id => $data) {
            $data['id'] = $id;
            $data['start'] = (int)$data['start'];
            $data['end'] = (int)$data['end'];
            $data['duration'] = (int)$data['duration'];
            $this->_periods[$id] = new Varien_Object($data);
        }
    }

    public function getAllCombinations()
    {
        $generatorBase = array();
        foreach ($this->_periods as $id => $period) {
            $generatorBase[] = $this->getPeriodChunks($period);
        }
        return $generatorBase;
        $all = $this->generateCombinations($generatorBase);
        usort($all, array($this, '_sortAll'));
        return $all;
    }

    protected function _sortCombinations($a, $b)
    {
        return $a['start']<$b['start'] ? -1 : ($a['start']>$b['start'] ? 1 : 0);
    }


    protected function _sortAll($a, $b)
    {
        return $a[0]['start']<$b[0]['start'] ? -1 : ($a[0]['start']>$b[0]['start'] ? 1 : 0);
    }




    /**
     * @param Varien_Object $period
     * @return array
     */
    protected function getPeriodChunks(Varien_Object $period)
    {
        $result = array();
        $duration = $period->getDuration();
        $id = $period->getId();
        for ($i = $period->getStart(); $i + $duration <= $period->getEnd(); $i += self::TIME_STEP) {
            $result[] = array('start' => $i, 'end' => $i + $duration, 'id' => $id);
        }
        return $result;
    }

    private $__combosResult = array();
    public function generateCombinations($data, $group = array(), $val = null, $i = 0)
    {
        if ($val !== null) {
            array_push($group, $val);
        } else {
            // first run
            $this->__combosResult = array();
        }
        if ($i >= count($data)) {
            if ($this->_isCombinationAcceptable($group)) {
                usort($group, array($this, '_sortCombination'));
                array_push($this->__combosResult, $group);
            }
        } else {
            foreach ($data[$i] as $v) {
                $this->generateCombinations($data, $group, $v, $i + 1);
            }
        }
        return $this->__combosResult;
    }

    protected function _isCombinationAcceptable($periods)
    {
        $minStart = null;
        $maxEnd = 0;
        $minLength = 0;
        $c = count($periods);
        for ($i = 0; $i < $c; $i++) {
            $period = $periods[$i];

            for ($k = $i + 1; $k < $c; $k++) {
                if ($this->arePeriodsIntersecting($period['start'],$period['end'],$periods[$k]['start'],$periods[$k]['end'])) {
                    return false;
//                } elseif ($periods[$k]['start'] - $period['end'] > 1800 || $period['start'] - $periods[$k]['end'] > 1800) {
//                    return true;
                }
            }

            if ($minStart === null) {
                $minStart = $period['start'];
            }
            $minStart = min($period['start'], $minStart);
            $maxEnd = max($period['end'], $maxEnd);
            $minLength += $period['end'] - $period['start'];
        }
        return ($maxEnd - $minStart) < $minLength + ($c - 1) * 100;
    }

    public function arePeriodsIntersecting($start1, $end1, $start2, $end2)
    {
        return $start1 < $end2 && $end1 > $start2;
    }

    public function isDeleteddd()
    {
        // input

        array(
            '1' => array('start' => 23423423, 'end' => 2342345345, 'duration' => 1800),
            '2' => array('start' => 23423423, 'end' => 2342345345, 'duration' => 1800),
            '3' => array('start' => 23423423, 'end' => 2342345345, 'duration' => 1800),
        );

        // output
        array(
            array(
                'time' => 234234234,
                'result' => array(
                    '1' => array('start' => 234234, 'end' => 2342342),
                    '2' => array('start' => 234234, 'end' => 2342342),
                    '3' => array('start' => 234234, 'end' => 2342342),
                )
            ),
            array(
                'time' => 234234234,
                'result' => array(
                    '1' => array('start' => 234234, 'end' => 2342342),
                    '2' => array('start' => 234234, 'end' => 2342342),
                    '3' => array('start' => 234234, 'end' => 2342342),
                )
            ),
            array(
                'time' => 234234234,
                'result' => array(
                    '1' => array('start' => 234234, 'end' => 2342342),
                    '2' => array('start' => 234234, 'end' => 2342342),
                    '3' => array('start' => 234234, 'end' => 2342342),
                )
            )
        );

    }

}
