<?php

/**
 * Directory sorter
 */
class Dir
{
    private $data = array();
    private $sortField = 'name';
    private $direction = 1;

    /**
     * Constructor
     *
     * @param string $path Directory to list/sort
     */
    public function __construct($path)
    {
        if(!@is_dir($path)) {
            throw new Exception("Could not find '$path', or it is not a directory.");
        }
        $handle = opendir($path);
        while($data = readdir($handle)) {
            $this->data[]['name'] = $data;
        }
        foreach($this->data as $ix => $data) {
            $this->data[$ix]['size'] = filesize($data['name']);
            $this->data[$ix]['timestamp'] = date('Y-m-d H:i:s', filemtime($data['name']));
            $this->data[$ix]['type'] = 'file';
            if(is_dir($path.DIRECTORY_SEPARATOR.$data['name'])) {
                $this->data[$ix]['type'] = 'directory';
            }
            elseif(strpos($data['name'], '.') === 0) {
                $this->data[$ix]['type'] = 'hidden';
            }
        }
    }

    /**
     * Sort by file name
     *
     * @param boolean $ascending (default: true)
     * @return Dir
     */
    public function sortByName($ascending=true)
    {
        $this->sortBy('name', $ascending);
        return $this;
    }

    /**
     * Sort by file mod timestamp
     *
     * @param boolean $ascending (default: true)
     * @return Dir
     */
    public function sortByTime($ascending=true)
    {
        $this->sortBy('timestamp', $ascending);
        return $this;
    }

    /**
     * Sort by file size
     *
     * @param boolean $ascending (default: true)
     * @return Dir
     */
    public function sortBySize($ascending=true)
    {
        $this->sortBy('size', $ascending);
        return $this;
    }

    private function sortBy($field, $ascending=true)
    {
        $this->sortField = $field;
        $this->direction = $ascending ? 1 : -1;
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
