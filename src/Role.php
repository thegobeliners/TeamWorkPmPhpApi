<?php namespace TeamWorkPm;

class Role extends Model
{

  protected function init()
  {
    $this->fields = [
      'name' => true,
      'description' => false,
      'users' => true,
    ];
    $this->action = 'roles';
  }

  public function get($id, $project_id = null)
  {
    $id = (int)$id;
    if ($id <= 0) {
      throw new Exception('Invalid param id');
    }
    $project_id = (int)$project_id;
    $action = "$this->action/$id";
    if ($project_id) {
      $action = "projects/$project_id/$this->action";
    }
    return $this->rest->get($action);
  }

  /**
   * Get all Roles (within a Project)
   * GET /projects/#{project_id}/roles
   * Retrieves all of the roles in a given project
   *
   * @param type $id
   * @return TeamWorkPm\Response\Model
   */
  public function getByProject($id)
  {
    $id = (int)$id;
    return $this->rest->get("projects/$id/$this->action");
  }

  /**
   * Create a role
   * POST /projects/#{project_id}/roles.xml
   * This will create a new role.
   *
   * @param array $data
   * @return mixed
   * @throws Exception
   */
  public function insert(array $data)
  {
    $project_id = empty($data['project_id']) ? 0 : (int)$data['project_id'];
    if ($project_id <= 0) {
      throw new \TeamWorkPm\Exception('Required field project_id');
    }
    return $this->rest->post("projects/$project_id/$this->action", $data);
  }

  /**
   * Update Role
   *
   * PUT /roles/#{role_id}
   *
   * Modifies an existing role.
   *
   * @param array $data
   * @return mixed
   * @throws Exception
   */
  public function update(array $data)
  {
    $id = empty($data['id']) ? 0  : (int) $data['id'];
    if ($id <= 0) {
      throw new \TeamWorkPm\Exception('Required field id');
    }
    unset($data['id']);
    return $this->rest->put("$this->action/$id", $data);
  }
}
