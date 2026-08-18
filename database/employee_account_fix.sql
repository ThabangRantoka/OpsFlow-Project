-- OpsFlow employee account linking fix
-- Run this once on an existing OpsFlow database.
-- Employee accounts can now be created before gender is supplied.

ALTER TABLE employees
    MODIFY gender ENUM('Male','Female') NULL DEFAULT NULL;
