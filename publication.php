<?php
/**
 * Publication page.
 * Created by PhpStorm.
 * User: max
 * Date: 15.03.18
 * Time: 14:05
 */

use components\models\Publications;
use components\widgets\CommentsPublication;

require "vendor/autoload.php";

// get publication data
$iPublication = (int) $_GET['id'];
$aPublication = Publications::instance()->publication($iPublication);
?>

<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link rel="stylesheet" href="/css/open-iconic-bootstrap.css" integrity="sha384-0Rn/KghDfSJqgXgyGY41BUI86DaLPoiaPLs+u7XC1VYGwoEvbzH/egGqzInmNqMG" crossorigin="anonymous">
		<link rel="stylesheet" href="/css/trumbowyg.min.css" integrity="sha384-W5XVmYyZ5Y2z+cy3yhGw6e3VGwZjvFlFND786V/q4M2aNogCAdLVJxGnDudl43D1" crossorigin="anonymous">
		<link rel="stylesheet" href="/css/styles.css" crossorigin="anonymous">

		<title>Публикатор 777 / <?= is_null($aPublication) ? 'Ошибка 404' : $aPublication['publication-subject']; ?></title>
	</head>
	<body>
		<header>
			<!-- Fixed navbar -->
			<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
				<a class="navbar-brand" href="/">Публикатор 777</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarCollapse">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item active">
							<a class="nav-link" href="/">Главная</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- Begin page content -->
		<main role="main" class="container col-xs-12 col-sm-12 col-md-10 col-lg-8 col-xl-8">
			<?php if (is_null($aPublication)): ?>
			<h1 class="mt-5">Ошибка 404</h1>
			<p class="lead">Документ не найден.</p>
			<?php else:?>
			<h1 class="mt-5 mb-3"><?= $aPublication['publication-subject']; ?></h1>
			<div class="mb-1">
				<small><?= $aPublication['publication-date']; ?> | <?= $aPublication['user-name']; ?></small>
			</div>
			<?= $aPublication['publication-content']; ?>

			<hr>

			<!-- Comments -->
			<div data-container="comments" data-url="/ajax/comment-last.php">
				<?= CommentsPublication::instance()->run(); ?>
			</div>

			<!-- Add a new comment -->
			<div class="card card-body bg-light mt-4" style="margin-bottom: 90px;">
				<h3>Добавьте комментарий</h3>
				<form id="comment-form" action="/ajax/comment-add.php" method="post" data-publication="<?= $iPublication;?>" onsubmit="return false;">
					<div class="form-row">
						<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label for="comment-email">Email</label>
							<input type="text" class="form-control required" id="comment-email" maxlength="60">
						</div>

						<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label for="comment-name">Имя <small>(только для регистрации)</small></label>
							<input type="text" class="form-control" id="comment-name" maxlength="100">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label for="comment-comment">Текст</label>
							<textarea class="form-control" id="comment-comment" rows="12"></textarea>
						</div>
					</div>

					<div class="response"></div>

					<button type="submit" class="btn btn-primary" onclick="Content.addComment();">Добавить комментарий</button>
				</form>
			</div>
			<?php endif; ?>
		</main>

		<footer class="footer">
			<div class="container">
				<span class="text-muted">Все права защищены. Серьёзно.</span>
			</div>
		</footer>

		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="/js/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
		<script src="/js/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
		<script src="/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="/js/trumbowyg/trumbowyg.min.js" integrity="sha384-wxJ8d4SQFmH6kEAQc3CUmzh5zKN9XI59e5G96937LH6J5hq9N7j9+y1vVowQr8Zi" crossorigin="anonymous"></script>
		<script src="/js/trumbowyg/langs/ru.min.js" integrity="sha384-oGLRDmXMWQr3JE55Q0/5Kf3st6y8TrmVNpc1n0df3YrnuniY07nffSk9t7KaAYoF" crossorigin="anonymous"></script>
		<script src="/js/jquery.color.js" integrity="sha384-sWyAcuy1ZTDKBQa7T/QXybXSCvGuVTf52F3mxPTA3nVCq8F417dHv5aiTpJsoLX5" crossorigin="anonymous"></script>
		<script src="/js/classes.js" crossorigin="anonymous"></script>
		<script src="/js/scripts.js" crossorigin="anonymous"></script>
	</body>
</html>