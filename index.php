<!DOCTYPE HTML>
<?php 
	include("helping_functions.php");
	

	$conf['name'] = "Site Name";
	$conf['tagline'] = "Site tagline";
	$conf['metaDesc'] = "Website description for fb-share";
	
	// Tagline button
	$conf['toggleImg'] = "Toggle Images";
	
	// Insert input
	$conf['linkPlaceHolder'] = 'http://www.your.net/nsfw/link?here=1';
	$conf['submit'] = "submit";

	// Messages and Errors
	$conf['linkDeleted'] = "Deleted the following link:";
	$conf['linkAdded'] = "The following link was added";
	$conf['sameLink'] = "This link already exists ;D";
	$conf['noLinks'] = "No links to show...";

	// FB Share Modal
	$conf['modalWarning'] = "Make sure you select <strong>FB-Group</strong> in the next window :)";
	$conf['modalWarningTag'] = "<small>This will only appear once.</small>";
	$conf['modalClose'] = "Close";
	$conf['modalOpenFBShare'] = "Thanks!";
	$conf['modalTitle'] = "Thanks!";

	// Redirecting user
	$conf['redirecting'] = "Redirecting status message";
	
	/*
	* No more Edits from this line on unless you know.
	*/


	$havelinks = false;
	$conf['homeLink'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	if (isset($_GET['admin'])) { $admin = true; }
	if ((isset($_GET['go'])) && (is_numeric($_GET['go']))) { 
		$followLink = $_GET['go']; 
		$success_class = "info";
		$returnMessage = $conf['redirecting'];
		$l = $links[$followLink];
	}
	
	if ($links = LoadDb("links.wallet")) {
		$havelinks = true;
		if (isset($_GET['d'])){
			if (is_numeric($_GET['d'])) {
				$deletelink = $_GET['d'];
				$count = count($links);
				if (isset($links[$deletelink])) {
					$returnMessage = $links['linkDeleted'];
					$l = $deletelink;
					$success_class = "danger";
					unset($links[$deletelink]);
					if (empty($links[$deletelink])) {
						$links = array_values($links);
						SaveDb($links,"links.wallet");
					}
				}
			}
		}
	}
	
	if (isset($_POST['link'])) {
		$l = strip_tags($_POST['link']);
		if ((!in_array($l,$links)) && (strlen($l)>0)) {
			$ls = LoadDb("links.wallet");
			$ls[] = $l;
			$insertlink = true;
			$returnMessage = $conf['linkAdded'];
			$success_class = "success";
			SaveDb($ls,"links.wallet");
		}
		else { $returnMessage = $conf['sameLink']; $success_class = "warning"; }
	}
?>
<head>
	<title><?php echo $conf['name'] ?></title>
	<script src="http://code.jquery.com/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<link href="css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="css/the.css" rel="stylesheet" media="screen">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta property="og:site_name" content="<?= $conf['name']; ?>"/>
	<meta property="og:description" content="<?= $conf['metaDesc']; ?>" />
	<meta property="og:title" content="<?= $conf['name']; ?>" />
	<?php if (isset($followLink)): ?>
	<meta http-equiv="refresh" content="3; URL=<?= $l ?>">
	<?php endif; ?>
</head>
<body>
	
	<div class="jumbotron">
		<div class="container">
			<h1><?php echo $conf['name'] ?></h1>
			<p><?= $conf['tagline']; ?></p>
			<button type="button" class="btn btn-info imgShow"><?= $conf['toggleImg']; ?></button>
			
			<div class="row"><ul class="imgFallHere list-inline list-unstyled"></ul></div>
		</div>
	</div>
	<div class="container">
		<form class="form-horizontal" method=post action="index.php" role="form">
			<div class="form-group">
				<div class="col-xs-10">
					<input type="url" class="form-control" id="link" name="link" placeholder="<?= $conf['linkPlaceHolder']; ?>">
				</div>
				<div class="col-xs-offset-1 col-xs-1">
				  <button type="submit" class="btn btn-default"><?= $conf['submit']; ?></button>
				</div>
				
			</div>
			<div class="form-group">

			</div>
		</form>
		

		<?php if (isset($returnMessage)): ?>
			<div class="alert alert-<?= $success_class ?>">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
				<h3 class="strong"><?= $returnMessage ?></h3>
				<p><? if($l) echo $l; ?></p>
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
			</div>
		<?php endif; ?>

		<div class="marginMeLikeOneOfYourFrenchElements">
			<?php if($havelinks == true): ?>
				<?php $links = LoadDb("links.wallet"); $totalLinks = count($links) - 1; ?>
				<?php for ($i = $totalLinks; $i >= 0; $i--): ?>
					
						<div class="row">
						        <div class="btn-group col-xs-12" style="margin-bottom:5px">
						                <button class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Share on Facebook"  data-href="<?= $i ?>"><i class="glyphicon glyphicon-share-alt"></i></button>
						                <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Follow link" href="<?php echo $links[$i]; ?>" target="_blank"><i class="glyphicon glyphicon-link"></i></a>
						                <a class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Right click to copy bro-link location" href="<?php echo $conf['homeLink']."?go=".$i; ?>" target="_blank"><i class="glyphicon glyphicon-pushpin"></i></a>
						                <?php if (isset($admin)): ?>
						                        <a data-toggle="tooltip" data-placement="top" title="Delete link"  class="btn btn-danger"   alt="Delete link" href="<?= $conf['homeLink'];?>&d=<?php echo $i; ?>"><i class="glyphicon glyphicon-remove"></i></a>
						                <?php endif; ?>
						                <input data-toggle="tooltip" data-placement="top" title="Click to select link" class="theInput btn btn-info <?php echo (isset($admin)) ? 'col-xs-9' : 'col-xs-10' ?>" type="text" value="<?= $links[$i]; ?>" style="text-align: left;" />
						        </div>
						</div>
					
				<?php endfor; ?>


			<?php else: ?>
			<h1 class="alert alert-warning"><?= $conf['noLinks'] ?></h1>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="modal">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?= $conf['modalClose'] ?></span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<p><?= $conf['modalWarning']; ?></p>
				<?= $conf['modalWarningTag']; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= $conf['modalClose'] ?></button>
				<button type="button" class="btn btn-primary alerted-ok" data-href=""><?= $conf['modalOpenFBShare'] ?>!</button>
			</div>
			</div>
		</div>
	</div>
	
	<script>
		$(document).ready(function() {
			$('[data-toggle=tooltip]').tooltip();
			$('.row button[data-href]').on('click',function() {
				var brolink = "<?= $conf['homeLink']; ?>?go="+$(this).attr('data-href');
				if (!localStorage.getItem('brolinkalert')) {
					$('.modal').modal('toggle');
					$('.modal .alerted-ok').attr('data-href',brolink);
				}
				else {
					window.open('http://facebook.com/sharer/sharer.php?u='+brolink,'','width=400,height=300'); 
				}
			});
			$('.alerted-ok').on('click',function() {
				var brolink = $(this).attr('data-href');
				$('.modal').modal('toggle');
				window.open('http://facebook.com/sharer/sharer.php?u='+brolink,'','width=400,height=300');
				localStorage.setItem('brolinkalert','1');
			});
			$('input.btn').on('click',function() {
				this.select();
			});
			$('a.btn-warning,a.btn-primary').on('hover',function() {
				$(this).tooltip({
					'animation':false,
					'title':'Share on Facebook'
				})
			});
			$('.imgShow').on('click',function() {
				var theInputs = $('input.theInput'),
					_this,_imgur,_that = $('.imgFallHere'),
					_myself = $(this),
					_clicked = $('.imgFallHere img').length;
				if (!_clicked) {
					theInputs.each(function(){
						_this = $(this),
						_val = _this.val();
						if (_val.search(/(.jpg|.gif|.gifv|.png)/g) > -1) {
							if (_val.search(/gallery\//g) > -1) _val = _val.replace('imgur.com','i.imgur.com').replace('/gallery','')+".jpg";
							_that.append('<li><a target="_blank" href="'+_val+'" ><img src="'+_val+'" class="smallSquare" /></a></li>');
						}
					});
				}
				
				else {
					$('.imgFallHere').toggleClass('hide');
				}				
			});
			
		});
	</script>
</body>
