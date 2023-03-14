<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;

/*
 * Console controller for managing RBAC roles and permissions.
 * This controller provides commands for creating roles and permissions,
 * assigning and revoking permissions from roles, and managing role hierarchies.
 */
class RbacController extends Controller {

	public function actionInit() {
		$auth = Yii::$app->authManager;
		\Yii::$app->db->createCommand()->delete($auth->itemChildTable)->execute();
		\Yii::$app->db->createCommand()->delete($auth->itemTable)->execute();
		\Yii::$app->db->createCommand()->delete($auth->ruleTable)->execute();
		$auth->invalidateCache();

		// Создание ролей
		$admin = $auth->createRole('admin');
		$auth->add($admin);
		$user = $auth->createRole('user');
		$auth->add($user);
		$guest = $auth->createRole('guest');
		$auth->add($guest);

		// Создание разрешений
		$create = $auth->createPermission('create');
		$auth->add($create);
		$view = $auth->createPermission('view');
		$auth->add($view);
		$updateOwn = $auth->createPermission('updateOwn');
		$auth->add($updateOwn);
		$delete = $auth->createPermission('delete');
		$auth->add($delete);

		// Назначение разрешений ролям
		$auth->addChild($admin, $create);
		$auth->addChild($admin, $view);
		$auth->addChild($admin, $delete);

		$auth->addChild($user, $create);
		$auth->addChild($user, $view);
		$auth->addChild($user, $updateOwn);

		$auth->addChild($guest, $create);
		$auth->addChild($guest, $view);

		// Назначение ролей пользователям
		$auth->assign($admin, 1);
		$auth->assign($user, 1);
		$auth->assign($user, 2);
		$auth->assign($user, 3);
		$auth->assign($user, 4);
		$auth->assign($user, 5);
		$auth->assign($user, 6);
		$auth->assign($user, 7);
		$auth->assign($user, 8);
		$auth->assign($user, 9);
		$auth->assign($user, 10);
	}

}