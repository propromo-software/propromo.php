<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home.index'));
});

// Home > Monitors
Breadcrumbs::for('monitors', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Monitors', route('monitors.index'));
});

Breadcrumbs::for('monitor', function (BreadcrumbTrail $trail, $monitor) {
    $trail->parent('monitors');
    $trail->push($monitor->title, route('monitors.show', $monitor));
});


Breadcrumbs::for('milestone', function (BreadcrumbTrail $trail, $monitor, $milestone) {
    $trail->parent('monitor', $monitor);
    $trail->push($milestone->title, route('milestone.show', [
        "monitor" => $monitor,
        "milestone" => $milestone
    ]));
});


Breadcrumbs::for('pdf', function (BreadcrumbTrail $trail, $monitor) {
    $trail->parent('monitor', $monitor);
    $trail->push('PDF', route('pdf.index', $monitor));
});
