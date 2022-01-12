<?php

namespace App\Models;

use App\Core\Model;

class Comment extends Model
{
    public $id;
    public $actuality_id;
    public $author_id;
    public $comment;

    static public function setDbColumns()
    {
        return ["id", "comment", "actuality_id", "author_id"];
    }

    static public function setTableName()
    {
        return "comments";
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getActualityId()
    {
        return $this->actuality_id;
    }

    /**
     * @param mixed $actuality_id
     */
    public function setActualityId($actuality_id): void
    {
        $this->actuality_id = $actuality_id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->comment;
    }

    /**
     * @param mixed $text
     */
    public function setText($comment): void
    {
        $this->comment = $comment;
    }
}