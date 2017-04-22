<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/21/17
 * Time: 10:32 PM
 */

namespace AppBundle\Entity;


class Ranking
{
    const NUMBER_OF_USERS_ON_RANKING_TABLE = 10;

    private $topUsersToday;

    private $topUsersThisWeek;

    private $topUsersThisMonth;
}