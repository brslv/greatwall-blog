<?php

/**
 * PostController
 */
class Post extends Main {

	/**
	 * The default method
	 *
	 */
	public function index() {
		Redirect::to('homepage');
	}

	/**
	 * Shows a specified post by id
	 *
	 * @param  int $id The id of the post
	 */
	public function show($id = null) {
		$commentModel = $this->getModel('CommentModel');
        $commentMsg = null; 

        if (isset($_POST['commentSubmit'])) {
        	if(!empty($_POST['commentContent'])) {
	            $comment = $_POST['commentContent'];
	            $author_id = $_SESSION['id'];
	            $post_id = $id[0];
	            $commentStatus = $commentModel->addComment($comment, $post_id, $author_id);

	            if($commentStatus) {
	            	$commentMsg = 'Thanks for the comment!';
	            } else {
	            	$commentMsg = 'Something went wrong. Please, Try again.';
	            }
	        } else {
	        	$commentMsg = 'Please, leave a meaningfull comment. I don\'t need your blank bullshits. Thanks! :)'; 
	        }
        }
       	
       	$comments = $commentModel->listComments($id[0]);

		$tag = $this->getModel('TagModel');
		$post = $this->getModel('PostModel');

		$post = $post->getPostById($id[0]);
		if (!$post) {
			Redirect::to('homepage');
		}

		$tags = $tag->get($post[0]['id']);

		$data = [
			'commentMsg' => $commentMsg,
			'post' => $post,
			'tags' => $tags,
            'comments' => $comments
		];

		$this->getView('singleView', $data);
	}

	/**
	 * Provides options to add and manage posts
	 *
	 */
	public function manage() {
		if (!$this->getModel('UserModel')->isAdmin()) {
			Redirect::to('homepage');
		}

		$msg = null;
		$viewData = null;
        $categoriesModel = $this->getModel('CategoryModel');
        
        $allPosts = $this->getModel('PostModel')->getPosts();
        $allCategories = $categoriesModel->getCategories();
		
		if (isset($_POST['postSubmit'])) {
			$post = $this->getModel('PostModel');
			$result = $post->addPost();

			if ($result) {
				$msg = 'Post added successfully. Refresh the page to see it in the manage section.';
			} else {
				$msg = 'Don\'t cheat, bro! Fill in all the blanks.';
			}
		}

		$data = [
			'msg' => $msg,
            'categories' => $allCategories,
            'posts' => $allPosts
		];

		$this->getView('admin/managePostsView', $data);
	}

	/**
	 * Delete a specified post
	 * 
	 * @param  int $id
	 */
	public function delete($id) {
		if(!isset($_SESSION['role'])) {
            if($id == null || $_SESSION['role'] != 'admin') {
                Redirect::to('homepage');
            }
        } else {
            $postModel = $this->getModel('PostModel');
            $postModel->delete($id[0]);
            Redirect::to('post/manage');
        }
	}

	/**
	 * Edit a specific post
	 * 
	 * @param  int $postId   
	 */
	public function edit($postId) {
		$postModel = $this->getModel('PostModel');
		$categories = $this->getModel('CategoryModel')->getCategories();
		if(!$this->getModel('UserModel')->isAdmin()) {
			Redirect::to('homepage');
		}

		if(empty($postId)) Redirect::to('post/manage');
		else $postId = $postId[0];

		$oldPostVersion = $postModel->getPostByid($postId);
		$msg = null;
		if(isset($_POST['updatePostSubmit'])) {
			if(!empty($_POST['newTitle']) && !empty($_POST['newContent'])) {
				if($postModel->update($postId)) {
					$msg = 'Successfully updated post';
				} else {
					$msg = 'Something went wrong. Please try again.';
				}

			}
		}

		$data = [
			'oldVersion' => $oldPostVersion,
			'msg' => $msg,
			'categories' => $categories
		];

		$this->getView('admin/editPostView', $data);
	}
}
