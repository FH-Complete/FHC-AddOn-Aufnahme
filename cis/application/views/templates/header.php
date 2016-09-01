<?php
/**
 * ./cis/application/views/templates/header.php
 *
 * @package default
 */


?>
<!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" context="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<meta name="description" content="">

	<title>Aufnahme</title>
	<link href="<?php echo base_url('../vendor/components/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('../vendor/components/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('../vendor/components/jqueryui/themes/base/jquery-ui.min.css'); ?>" rel="stylesheet">
	<link href="<?php echo base_url('themes/' . $this->config->item('theme') . '/global.css'); ?>" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<script src="<?php echo base_url('../vendor/components/jquery/jquery.min.js') ?>"></script>
	<script src="<?php echo base_url('../vendor/components/jqueryui/jquery-ui.min.js') ?>"></script>
	<script src="<?php echo base_url('../include/js/lodash/lodash.min.js') ?>"></script>
	<script src="<?php echo base_url('../vendor/components/bootstrap/js/bootstrap.min.js') ?>"></script>
	<script src="<?php echo base_url('themes/' . $this->config->item('theme') . '/global.js') ?>"></script>
	<script type="text/javascript">
	    $(document).ready(function(){
		$.ajaxSetup({
		    headers: {
			'<?php echo $this->config->item('fhc_api')['api_name']; ?>': '<?php echo $this->config->item('fhc_api')['api_key']; ?>',
			"Authorization": "Basic " + '<?php echo base64_encode($this->config->item('fhc_api')['http_user'].":".$this->config->item('fhc_api')['http_pass']); ?>'
		    }
		});
	    });
	</script>
    </head>
    <body>
