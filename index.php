<!DOCTYPE HTML>
<?php 
	include("helping_functions.php");
	
	$conf['name'] = "Brolinks";
	$conf['linkPlaceHolder'] = 'http://www.your.net/nsfw/link?here=1';
	$conf['linkDeleted'] = "Deleted the following link:";
	$conf['linkAdded'] = "The following link was added";
	$conf['tagline'] = "The NSFW Collection";
	$conf['submit'] = "submit";
	$conf['homeLink'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$conf['modalWarning'] = "Make sure you select <strong>Brogurians</strong> in the next window :)";
	$conf['modalWarningTag'] = "<small>This will only appear once.</small>";
	$conf['redirecting'] = "Hold tight, browser will redirect you.";
	
	if (isset($_GET['admin'])) { $admin = true; }
	if (isset($_GET['go'])) { $followLink = $_GET['go']; }
	
	$havelinks = false;

	if ($links = LoadDb("links.wallet")) {
		$havelinks = true;
		if (isset($_GET['d'])){
			if (is_numeric($_GET['d'])) {
				$deletelink = $_GET['d'];
				$count = count($links);
				if (isset($links[$deletelink])) {
					$deletedlinkstring = $links[$deletelink];
					unset($links[$deletelink]);
					if (empty($links[$deletelink])) {
						$deletedlink = true;
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
			if (SaveDb($ls,"links.wallet")) {
				$success = true;
			}
			else $success = false;
		}
		else { $samelink = true; }
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
	<meta property="og:description" content="NSFW Links." />
	<meta property="og:title" content="<?= $conf['name']; ?>" />
	<?php if (isset($followLink)): ?>
	<meta http-equiv="refresh" content="3; URL=<?php echo $links[$followLink]; ?>">
	<?php endif; ?>
</head>
<body>
	
	<div class="jumbotron">
		<div class="container">
			<h1><?php echo $conf['name'] ?></h1>
			<p><?= $conf['tagline']; ?></p>
			<button type="button" class="btn btn-info imgShow" open-status="closed">Show Images</button>
			
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
		<?php if (isset($samelink)):?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
				<h3 class="strong">This link is so awesome..</h3>
				<p>... It got posted <strong>twice!</strong></p>
			</div>
		<?php endif; ?>
		<?php if (isset($insertlink)):?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
				<h3 class="strong"><?= $conf['linkAdded'] ?></h3>
				<p><?php echo $l; ?></p>
			</div>
		<?php endif; ?>
		<?php if (!empty($deletedlink)):?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
				<h3 class="strong"><?= $conf['linkDeleted'] ?></h3>
				<p><?php echo $deletedlinkstring; ?></p>
			</div>
		<?php endif; ?>
		<?php if (isset($followLink)):?>
			<div class="alert alert-info">
				<a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
				<h3 class="text-muted"><?= $conf['redirecting']; ?></h3>
				<p class=""><?= $links[$followLink] ?></p>
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
			<h1 class="alert alert-warning">No links to show...</h1>
			<?php endif; ?>
		</div>
	</div>
	
	<div class="modal">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Sharing a NSFW link</h4>
			</div>
			<div class="modal-body">
				<p><?= $conf['modalWarning']; ?></p>
				<?= $conf['modalWarningTag']; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary alerted-ok" data-href="">Thanks!</button>
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
					window.open('http://facebook.com/sharer/sharer.php?u='+brolink,'share_brolink','width=400,height=300'); 
				}
			});
			$('.alerted-ok').on('click',function() {
				var brolink = $(this).attr('data-href');
				$('.modal').modal('toggle');
				window.open('http://facebook.com/sharer/sharer.php?u='+brolink,'share_brolink','width=400,height=300');
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
					_action = _myself.attr('open-status');
				if (_action === "closed") {
					theInputs.each(function(){
						_this = $(this),
						_val = _this.val();
						if (_val.search(/(.jpg|.gif|.gifv|.png)/g) > -1) {
							_that.append('<li><a target="_blank" href="'+_val+'" ><img src="'+_val+'" class="smallSquare" /></a></li>');
						}
						if (_val.search(/gallery\//g) > -1) {
							console.log('found an imgur gallery link!'+_val);
							_imgur = _val.replace('imgur.com','i.imgur.com').replace('/gallery','')+".jpg";
							_that.append('<li><a target="_blank" href="'+_val+'" ><img src="'+_imgur+'" class="smallSquare" /></a></li>');
						}
					});
					_myself.attr('open-status','showing');
					_myself.text('Hide Images');
				}
				if (_action === "showing") {
					$('ul img').addClass('hide');
					_myself.attr('open-status','hiding');
					_myself.text('Show Images');
				}
				if (_action === "hiding") {
					$('ul img').removeClass('hide');
					_myself.attr('open-status','showing');
					_myself.text('Hide Images');
				}
				
			});
			
		});
	</script>
</body>
