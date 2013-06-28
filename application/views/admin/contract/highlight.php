<div class="row">
	<style>
		div.contract-image{
			position: relative;
			border: 1px solid black;
		}
		
		div.contract-image > img{
			display: block;
			padding: 10px;
			width: 100%;
			height:200px;
			background:url('<?php echo $contract_page ?>') 0 0;
			background-position: -88px -342px;
			background-size: 959px 1290px;
			background-repeat: no-repeat;
		}
		
		div.contract-image > div.highlighter {
			width: 100%;
			position: absolute;
			top: 102px;
			background: rgb(255, 255, 0); /* fallback color */
		  	background: rgba(255, 255, 0, 0.6);
			height: 10px;
			min-height: 10px;
		}
		
	</style>
	
	<div class="contract-image">	
		<img src="blank.gif" width="1" height="1">
		<div class="highlighter">
			&nbsp;
		</div>
	</div>
	
</div>