<?php

namespace Src\Domain\Grant;

enum Grants: string {
    case CREATE_USERS = 'auth.create.users';
    case READ_USERS = 'auth.read.users';
    case UPDATE_USERS = 'auth.update.users'; 
    case DELETE_USERS = 'auth.delete.users';
    case ASSIGN_GRANTS = 'auth.assign.grants';
}