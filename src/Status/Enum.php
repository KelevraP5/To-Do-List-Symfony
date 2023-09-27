<?php

use App\Status;

Enum StatusTask{
    const Finished = 'fini';
    const Ongoing = 'en cours';
    const ToDo = 'à faire';

    public static function getStatusChoices() {
        return [
            self::Finished => self::Finished,
            self::Ongoing => self::Ongoing,
            self::ToDo => self::ToDo,
        ];
    }
}