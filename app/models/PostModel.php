<?php

class PostModel extends MainModel {

    public function addPost(){

        if(!empty($_POST['postTitle']) && !empty($_POST['postContent'])) {
            $insertInfo = $this->db->prepare('INSERT INTO posts(title,content) VALUE (:postTitle,:postContent)');
            return $insertInfo->execute([
                ':postTitle' => $_POST['postTitle'],
                ':postContent' => $_POST['postContent']
            ]);

        }

        return false;

    }

}
