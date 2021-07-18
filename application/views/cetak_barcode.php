<html>
<head>
<style>
p.inline {display: inline-block;}
span { font-size: 13px;}
</style>
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */

    }
</style>
</head>
<body >
	<div style="margin-left: 0%">
		<table cellpadding=0 cellspacing=0>
			<?php for($baris = 1; $baris < $qty; $baris++ )  { ?>
				<?php for($kolom = 1; $kolom < $qty; $kolom++) { ?>
					<td style="padding-top: 15px;"><img src="<?php echo base_url('/barcode/generate_barcode?&barcode='.$barcode);?>" style="margin:0px 0px; padding:0px 0px;"></td>
				<?php } ?>
				<tr>
					
				</tr>
			<?php } ?>
		</table>
	</div>
	<script>
		window.print()
	</script>
</body>
</html>
