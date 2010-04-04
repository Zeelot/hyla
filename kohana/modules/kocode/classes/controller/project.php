<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Project extends Controller_Template_Kocode
{
	public function action_index()
	{
		$this->template->content = View::factory('project/list')
			->bind('projects', $projects);

		$projects = ORM::factory('project')->find_all();
	}

	public function action_details()
	{
		$this->template->content = View::factory('projects/detail')
			->bind('project', $project);

#		$project = Sprig::factory('project', array('name' => $this->request->param('name')))
#			->load();

		if ( ! $project->loaded())
		{
			$this->request->redirect(Route::get('project')->uri());
		}
	}

	public function action_create()
	{
		$this->template->content = View::factory('projects/edit')
			->bind('project', $project)
			->bind('errors', $errors);

#		$project = Sprig::factory('project');

		if ($name = $this->request->param('name'))
		{
			$project->name = $name;
		}

		if ($_POST)
		{
			try
			{
				$project->values($_POST)->create();

				$this->request->redirect(Route::get('project')->uri(array('name' => $project->name)));
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('project/edit');
			}
		}
	}

	public function action_update()
	{
		$this->template->content = View::factory('projects/edit')
			->bind('project', $project)
			->bind('errors', $errors);

#		$project = Sprig::factory('project', array('name' => $this->request->param('name')))
#			->load();

		if ( ! $project->loaded())
		{
			$this->request->redirect($this->request->uri(array('action' => 'create')));
		}

		if ($_POST)
		{
			try
			{
				$project->values($_POST)->update();

				$this->request->redirect(Route::get('project')->uri(array('name' => $project->name)));
			}
			catch (Validate_Exception $e)
			{
				$errors = $e->array->errors('project/edit');
			}
		}
	}

} // End Projects
