# About Propromo

<div align="center">
    <img src="https://github.com/propromo-software/propromo.php/blob/7a06b7bca13f1663a2652c17235b99f10bb490e8/public/favicon.png" alt="favicon" width="200" />
</div>

<br>

<div align="center">
    <span style="font-size: 1.5rem;">
      <b><strong>Pro|pro|mo</strong></b> - Project Progress Monitoring
    </span>
</div>

<p></p>

<div align="center" >
  <a href="https://propromo-docs.vercel.app/guides/join-monitor" target="_blank">
    <img src="./docs/README.hero.svg" alt="Project Progress Monitoring Hero Section" />
  </a>
</div>

---

> Your client wants to know what's up. [Propromo](https://propromo.duckdns.org) makes it possible.

## The Project

### Why?

#### Introduction - Initial Situation

As a customer, I want to know how far along the project I commissioned has progressed. As a seller, I want to fulfill this request with minimal effort.

> [!NOTE]  
> **Example: Client**  
> I commissioned a project and haven't heard anything about it for two months
> Therefore, I write an email to arrange a meeting.

#### Problem and Target Audience

At some point, as a client, the whole process becomes too frustrating, both in terms of cost and time. The contractor (freelancer, student, small to medium-sized agile software company) then invites me into the internal SCRUM system. This costs them more time because they need to grant me different permissions and possibly repeatedly invite an employee from my company who also needs access. Additionally, every SCRUM system is more complex than the next, and I have no desire to familiarize myself with new software.

> [!TIP]
> Limited insight into a project often leads to dissatisfaction on the part of the
> client and/or the contractor.

### How?

#### Solution - What Propromo Does

> Propromo consolidates all common SCRUM systems into a single UI.

-   When clients can see what they are getting, they are willing to pay more for it.
-   The customer has free access to the project status, and no additional meetings are necessary. This saves both time and money.
-   The quality of the project can be improved since the customer has direct access to the team's SCRUM master.
-   ONLY the SCRUM master communicates with the client, allowing other team members to focus on their specific tasks.

[Read about Propromos KPIs](https://propromo-docs.vercel.app/reference/kpis)

#### Propromo's Features

> Propromo creates transparency, and transparency builds trust and enhances credibility.

-   View basic project information
-   View milestones, tasks, and sprints of a project
-   Checkout Deployments and Releases
-   Chat with the SCRUM master

## Statistics

[![GitHub license](https://img.shields.io/github/license/propromo-software/propromo.php.svg)](https://github.com/propromo-software/propromo.php/blob/main/LICENSE)
[![GitHub contributors](https://img.shields.io/github/contributors/propromo-software/propromo.php.svg)](https://github.com/propromo-software/propromo.php/graphs/contributors)
![GitHub watchers](https://img.shields.io/github/watchers/propromo-software/propromo.php?style=flat)
[![GitHub stars](https://badgen.net/github/stars/propromo-software/propromo.php)](https://GitHub.com/propromo-software/propromo.php/stargazers/)
[![GitHub forks](https://badgen.net/github/forks/propromo-software/propromo.php/)](https://GitHub.com/propromo-software/propromo.php/network/)

[![GitHub issues](https://img.shields.io/github/issues/propromo-software/propromo.php.svg)](https://github.com/propromo-software/propromo.php/issues/)
[![GitHub issues-closed](https://img.shields.io/github/issues-closed/propromo-software/propromo.php.svg?color=success)](https://GitHub.com/propromo-software/propromo.php/issues?q=is%3Aissue+is%3Aclosed)
![GitHub Total Releases](https://badgen.net/github/releases/propromo-software/propromo.php)
![GitHub release](https://img.shields.io/github/release/propromo-software/propromo.php?include_prereleases)
[![Github all releases](https://img.shields.io/github/downloads/propromo-software/propromo.php/total.svg?include_prereleases)](https://GitHub.com/propromo-software/propromo.php/releases/)
[![GitHub commits](https://badgen.net/github/commits/propromo-software/propromo.php)](https://GitHub.com/propromo-software/propromo.php/commit/)
[![Commit Activity](https://img.shields.io/github/commit-activity/m/propromo-software/propromo.php)](https://github.com/propromo-software/propromo.php/commits/)
[![GitHub latest commit](https://badgen.net/github/last-commit/propromo-software/propromo.php)](https://GitHub.com/propromo-software/propromo.php/commit/)

## Development

![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Vite](https://img.shields.io/badge/vite-%23646CFF.svg?style=for-the-badge&logo=vite&logoColor=white)
![NodeJS](https://img.shields.io/badge/Node%20js-339933?style=for-the-badge&logo=nodedotjs&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/postgres-%23316192.svg?style=for-the-badge&logo=postgresql&logoColor=white)
![Redis](https://img.shields.io/badge/redis-%23DD0031.svg?style=for-the-badge&logo=redis&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white)
![GitHub Actions](https://img.shields.io/badge/github%20actions-%232671E5.svg?style=for-the-badge&logo=githubactions&logoColor=white)
![Kubernetes](https://img.shields.io/badge/Kubernetes-3069DE?style=for-the-badge&logo=kubernetes&logoColor=white)

[![.github/workflows/ci.yml](https://github.com/propromo-software/propromo.php/actions/workflows/ci.yml/badge.svg)](https://github.com/propromo-software/propromo.php/actions/workflows/ci.yml)
[![Continuous Deployment/Release - Website](https://github.com/propromo-software/propromo.php/actions/workflows/release.yml/badge.svg)](https://github.com/propromo-software/propromo.php/actions/workflows/release.yml)

### Install Dependencies & Run

```bash
start.sh
```

```batch
start.cmd
```

### One By One

#### Database

```bash
docker-compose -f ./docker/postgres.yml up -d
```

#### Cache

```bash
docker-compose -f ./docker/redis.yml up -d
```

#### Website

```bash
php artisan serve --port=80
```

### Testing

![PHP Code Coverage Badge](https://propromo-software.github.io/propromo.php/coverage.svg)

```bash
php ./vendor/bin/pest
```

## Production

[![Better Stack Badge](https://uptime.betterstack.com/status-badges/v1/monitor/zuzz.svg)](https://dub.sh/propromo-status)

**Deployment URLs:**

-   https://propromo.duckdns.org (points to heroku)
-   https://propromo.dnset.com (points to heroku)
-   https://propromo.simulatan.me (points to heroku)
-   https://propromo-d08144c627d3.herokuapp.com (main)

## Design

-   Core wire-frames: [figma.com/propromo](https://dub.sh/propromo-wireframes)
-   Documentation: [propromo-docs](https://propromo-docs.vercel.app/reference/design)

## Team

<a href="https://github.com/propromo-software/propromo.php/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=propromo-software/propromo.php" />
</a>
