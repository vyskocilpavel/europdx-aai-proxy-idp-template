<?php
if(!empty($this->data['htmlinject']['htmlContentPost'])) {
	foreach($this->data['htmlinject']['htmlContentPost'] AS $c) {
		echo $c;
	}
}
?>
</div><!-- #content -->
</div><!-- #wrap -->

<div id="footer">

    <div class="row" style="margin: 0px auto; max-width: 1000px;">

	<div class="col-md-6" style="float: left;">
		<img src="<?php echo SimpleSAML\Module::getModuleUrl('europdx/res/img/eu_flag_128.png') ?>">
        <p>The EDIReX project has received funding from the European Union’s Horizon 2020 research and innovation programme, grant agreement no. #731105</p>

    </div>
	
	<div class="col-md-6" style="float: right;">
        <ul>
            <li>
                <a href="http://www.twitter.com/EurOPDX"> Follow @EUROPDX</a>
            </li>
            <li>
                <a href="https://europdx.eu/#"> TERMS OF USE</a>
            </li>
        </ul>
	</div>

    </div>
    <div class="row" style="text-align: center">
        <div class="col-md-12 copyright">
            <p> © 1991– <?php echo date("Y"); ?> | EuroPDX -
                <a href="mailto:contact@europdx.eu"> contact@europdx.eu </a>
            </p>
        </div>
    </div>
	
</div><!-- #footer -->

</body>
</html>

