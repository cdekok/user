<?php
namespace Cept\User\Model;

class Role implements \Phapp\Hydrate\HydrateInterface {
    
    use \Phapp\Hydrate\HydrateTrait;
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $description;
    
    /**
     * Parent role
     * @var string
     */
    protected $parent;
    
    /**
     * Created date time
     * @var string
     */
    protected $created;
    
    /**
     * Modified date time
     * @var string
     */
    protected $modified;

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getModified() {
        return $this->modified;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    public function setModified($modified) {
        $this->modified = $modified;
        return $this;
    }  
    
    public function getParent() {
        return $this->parent;
    }

    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }
}
