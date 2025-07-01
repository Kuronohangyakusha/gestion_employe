<?php

namespace Entity;

enum Type: string {
    case Admin = 'Admin';
    case Developpeur = 'Developpeur';
    case Manager = 'Manager';
}