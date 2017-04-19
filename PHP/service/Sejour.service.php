<?php

/************************************************************************************************************************************/
/*																																  */
/*	Sejour.service.php
/*	Auteur : Antoine Alleard
/*	Date : 17/02/2017 18:19:38
/*	Description : 
/*																																  */
/************************************************************************************************************************************/

	/* Service's main object */
	require_once(PHP_PATH.'classes/Sejour.class.php');

	/* Init error flag */
	$lv_success = true;

	/* Query parameters from URL */
	if (isset($gt_request[2])) {
		$gt_parameters['uid'] = $gt_request[2];
	}

	switch ($_SERVER['REQUEST_METHOD']) {

		case 'POST':
			if (isset($gt_data['t_sejour'])) {
				foreach ($gt_data['t_sejour'] as $lo_object_data) {
					$lt_data = [];
					foreach ($lo_object_data as $lv_key => $lv_value) {
						$lv_key = Utilities::convertJsToPhpVar($lv_key);
						$lt_data[$lv_key] = $lv_value;
					}
					$lo_sejour = new Sejour($lt_data);
					$lo_sejour->save($lv_success);
					Sejour::bufferize($lo_sejour);
				}
			} else {
				Message::bufferMessage(new Message('api', 3, 'x'));
				/* No record was transmitted to API, check format */
			}

			break;

		case 'PUT':
			$lt_results = SejourManager::get($gt_parameters, $gt_sorters, $gt_expand);
			if (count($lt_results) <= 1) {
				$lo_sejour = new Sejour($gt_data);
				$lo_sejour->setUid($gt_request[2]);
				$lo_sejour->save($lv_success);
				Sejour::bufferize($lo_sejour);
			} else {
				/* Several results, no mass update */
				Message::bufferMessage(new Message('api', 4, 'x'));
				/* Several recors have been found, fonction impossible */
			}

			break;

		case 'GET':
			/* Search */
			$lt_results = SejourManager::get($gt_parameters, $gt_sorters, $gt_expand, $gv_limit);
			foreach ($lt_results as $lo_sejour) {
				Sejour::bufferize($lo_sejour);
			}

			break;

		case 'DELETE':
			/* Search record */
			$lt_results = SejourManager::get($gt_parameters, $gt_sorters, $gt_expand);
			switch (count($lt_results)) {

				case 0:
					/* No result */
					Message::bufferMessage(new Message('api', 5, 'x'));
					break;

				case 1:
					/* Unique record, will be deleted */
					$lo_sejour = $lt_results[0];
					$lo_sejour->delete($lv_success);
					Sejour::bufferize($lo_sejour);
					break;

				default:
					/* Several records, no mass deletion by default */
					Message::bufferMessage(new Message('api', 4, 'x'));
					break;

			}

			break;

		default:
			/* code... */
			break;
	}

	$go_output->t_sejour = Sejour::getBuffer();

?>
