<?php
/**
 * ./cis/application/views/language.php
 *
 * @package default
 */


?>
<div class="dropdown pull-right">
    <button class="btn btn-default dropdown-toggle" type="button" id="language-label" data-toggle="dropdown" aria-expanded="true">
	<?php echo $this->lang->line($sprache); ?>
	<span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="language-label" id="language-dropdown">
	<li role="presentation">
	    <a href="#" role="menuitem" tabindex="-1" data-language="english"><?php echo $this->lang->line('english'); ?></a>
	</li>
	<li role="presentation">
	    <a href="#" role="menuitem" tabindex="-1" data-language="german"><?php echo $this->lang->line('german'); ?></a>
	</li>
    </ul>

    <script type="text/javascript">
	function changeLanguage(language)
	{
	    var method = '';
	    window.location.href = "?language=" + language + "&studiengang_kz=<?php echo (isset($studiengang)) ? $studiengang->studiengang_kz :  ''; ?>";
	}

	$(function () {
	    $('#language-dropdown a').on('click', function () {
		var language = $(this).attr('data-language');
		changeLanguage(language);
	    });
	});
    </script>
</div>
