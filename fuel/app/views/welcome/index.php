    <div class="contents index"> <!-- class="container" -->
        <?php if(!$current_user) { ?>
    	<div class="jumbotron">
    		<h1> Videokaste.lv </h1>
    		<div class="desc">
    		    <p>
    				Mūsu sistēma sniedz ērtu un intuitīvu veidu kā lietotājiem atrast un ievietot savas video pamācības
                    par dažādām tēmām piemēram - Web tehnoloģiju izstrāde, video montēšana, ēdienu gatavošana u.c. 
                    Video pamācību veidotāji, blakus savām pamācībām, var izvietot reklāmas, kas, savukārt,
                    var sniegt nepieciešamos resursus jauna un kvalitatīva satura veidošanai.
    			</p>
    			<a class="btn btn-default pull-right btn-lg" href="/about">Par projektu</a>
                <a href="/users/login" class="btn btn-default pull-right btn-lg" >Ieiet</a>
    		    <a href="/users/create" class="btn btn-primary pull-right btn-lg" >Reģistrēties</a>
    		</div>
    	</div>
    	<div class="stats">Reģistrēti lietotāji: <?php echo $total_users; ?> | Pamācības: <?php echo $total_tutorials; ?></div>
        <?php } ?>

    </div> <!-- /container -->