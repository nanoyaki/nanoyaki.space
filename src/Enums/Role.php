<?php

namespace App\Enums;

enum Role: string {
    case User = "ROLE_USER";
    case Admin = "ROLE_ADMIN";
}