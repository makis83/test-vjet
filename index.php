<?php
/**
 * Index page.
 * Created by PhpStorm.
 * User: max
 * Date: 15.03.18
 * Time: 14:05
 */

use components\widgets\PublicationsAll;
use components\widgets\PublicationsTop;

require "vendor/autoload.php";
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

		<title>Публикатор 777</title>
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
							<a class="nav-link" href="/">Главная <span class="sr-only">(current)</span></a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="javascript:void(0);" onclick="Helper.scrollTo($('#publication-form').parent('.card'));">Добавить публикацию</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- Begin page content -->
		<main role="main" class="container col-xs-12 col-sm-12 col-md-10 col-lg-8 col-xl-8">
			<div class="jumbotron mt-4">
				<h1 class="display-4">Публикатор 777</h1>
				<p class="lead">Это &laquo;Публикатор 777&raquo;&nbsp;&mdash; передовой блог о&nbsp;технологиях в&nbsp;пределах Млечного пути.</p>
				<hr class="my-4">
				<p>Не робейте! Добавляйте публикации и оставляйте комментарии.</p>
				<p class="lead">
					<a class="btn btn-primary btn-lg" href="javascript:void(0);" role="button" onclick="Helper.scrollTo($('#publication-form').parent('.card'));">Добавить!</a>
				</p>
			</div>

			<!-- Top publications -->
			<div data-container="publications-top" data-url="/ajax/publications-top.php">
				<?= PublicationsTop::instance()->run(); ?>
			</div>

			<!-- All publications -->
			<div data-container="publications-all" data-url="/ajax/publication-last.php">
				<?= PublicationsAll::instance()->run(); ?>
			</div>

			<div class="card card-body bg-light mt-4" style="margin-bottom: 90px;">
				<h3>Добавьте свою публикацию</h3>
				<form id="publication-form" action="/ajax/publication-add.php" method="post" onsubmit="return false;">
					<div class="form-row">
						<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label for="publication-email">Email</label>
							<input type="text" class="form-control required" id="publication-email" maxlength="60">
						</div>

						<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<label for="publication-name">Имя <small>(только для регистрации)</small></label>
							<input type="text" class="form-control" id="publication-name" maxlength="100">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label for="publication-subject">Тема</label>
							<input type="text" class="form-control required" id="publication-subject" maxlength="120">
						</div>
					</div>

					<div class="form-row">
						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<label for="publication-content">Текст</label>
							<textarea class="form-control" id="publication-content" rows="12"></textarea>
						</div>
					</div>

					<div class="response"></div>

					<button type="submit" class="btn btn-primary" onclick="Content.addPublication();">Добавить публикацию</button>
				</form>
			</div>
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