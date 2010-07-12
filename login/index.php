<?php
/**
 * @package Swiftlet
 * @copyright 2009 ElbertF http://elbertf.com
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 */

$contrSetup = array(
	'rootPath'  => '../',
	'pageTitle' => ( isset($_GET['logout']) ? 'Log out' : 'Log in' )
	);

require($contrSetup['rootPath'] . '_model/init.php');

$model->check_dependencies(array('db', 'session', 'user', 'form'));

$model->form->validate(array(
	'form-submit' => 'bool',
	'username'    => 'string',
	'password'    => 'string',
	'action'      => 'string'
	));

if ( isset($model->GET_raw['logout']) )
{
	if ( !$model->POST_valid['confirm'] )
	{
		$model->confirm($model->t('Do you want to log out?'));
	}
	else
	{
		$model->user->logout();

		header('Location: ?notice=logout');

		$model->end();
	}
}

if ( $model->POST_valid['form-submit'] )
{
	if ( $model->form->errors )
	{
		$view->error = $model->t('Please provide a username and password.');
	}
	else
	{
		/*
		 * Limit the number of login attempts to 1 per 3 seconds
 		 */
		$model->db->sql('
			SELECT
				`date_login_attempt`
			FROM `' . $model->db->prefix . 'users`
			WHERE
				`username` = "' . $model->POST_html_safe['username'] . '"
			LIMIT 1
			;');

		if ( isset($model->db->result[0]) && $r = $model->db->result[0] )
		{
			if ( strtotime($r['date_login_attempt']) > gmmktime() - 3 )
			{
				$view->error = $model->t('Only one log in attempt per 3 seconds allowed, please try again.');
			}
			else
			{
				$r = $model->user->login($model->POST_html_safe['username'], $model->POST_raw['password']);

				if ( $r )
				{
					if ( !empty($model->GET_raw['ref']) )
					{
						/*
						 * Header injection is not an issue here, header()
						 * prevents more than one header to be sent at once
						 */
						header('Location: ' . $model->GET_raw['ref']);

						$model->end();
					}

					header('Location: ?notice=login');

					$model->end();
				}
				else
				{
					$view->error = $model->t('Incorrect password, try again.');
				}
			}
		}
		else
		{
			$view->error = $model->t('Sorry, we have no record of that username.');
		}

	}
}

if ( isset($model->GET_raw['ref']) && empty($view->error) )
{
	$view->notice = $model->t('Please log in with an authenticated account.');
}

if ( $model->session->get('user id') == user::guestId )
{
	$model->db->sql('
		SELECT
			`date_login_attempt`
		FROM `' . $model->db->prefix . 'users`
		WHERE
			`date_login_attempt` AND
			`id` = 1
		LIMIT 1
		;');

	if ( empty($model->db->result) )
	{
		$view->notice = $model->t('An account has been created with username "Admin" and the system password (%1$s in %2$s).', array('<code>sysPassword</code>', '<code>/_config.php</code>'));
	}
}

if ( isset($model->GET_raw['notice']) )
{
	switch ( $model->GET_raw['notice'] )
	{
		case 'login':
			if ( $model->session->get('user id') != user::guestId )
			{
				$view->notice = $model->t('Hello %1$s, you are now logged in.', $model->session->get('user username'));
			}

			break;
		case 'logout':
			if ( $model->session->get('user id') == user::guestId )
			{
				$view->notice = $model->t('You are now logged out.');
			}

			break;
	}
}

$view->load('login.html.php');

$model->end();
