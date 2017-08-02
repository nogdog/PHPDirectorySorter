<?php

/**
 * Directory sorter
 * Warning: no error-checking at this point!
 */
class Dir
{
    private $data = array();
    private $sortField = 'name';
    private $direction = 1;

    public function __construct($path)
    {
        $handle = opendir($path);
        while($data = readdir($handle)) {
            $this->data[]['name'] = $data;
        }
        foreach($this->data as $ix => $data) {
            $this->data[$ix]['size'] = filesize($data['name']);
            $this->data[$ix]['timestamp'] = date('Y-m-d H:i:s', filemtime($data['name']));
        }
    }

    public function sortByName($asc = true)
    {
        $this->sortBy('name', $asc);
    }

    public function sortByTime($asc=true)
    {
        $this->sortBy('timestamp', $asc);
    }

    public function sortBySize($asc=true)
    {
        $this->sortBy('size', $asc);
    }

    private function sortBy($field, $asc=true)
    {
        $this->sortField = $field;
        $this->direction = $asc ? 1 : -1;
        usort($this->data, array($this, 'sort'));
    }

    private function sort($a, $b) {
        return strnatcasecmp($a[$this->sortField], $b[$this->sortField]) * $this->direction;
    }

    public function data()
    {
        return $this->data;
    }
}
