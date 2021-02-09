<?php

namespace GeminiLabs\SiteReviews\Database;

use GeminiLabs\SiteReviews\Database;
use GeminiLabs\SiteReviews\Helpers\Str;

class SqlSchema
{
    /**
     * @var array|null
     */
    protected $constraints;

    /**
     * @var \wpdb
     */
    protected $db;

    /**
     * @var array|null
     */
    protected $tables;

    public function __construct()
    {
        require_once ABSPATH.'wp-admin/includes/upgrade.php';
        global $wpdb;
        $this->db = $wpdb;
    }

    /**
     * @return void
     */
    public function addAssignedPostsForeignConstraints()
    {
        $this->addForeignConstraint(
            $table = $this->table('assigned_posts'),
            $constraint = $this->foreignConstraint('assigned_posts_rating_id'),
            $foreignKey = 'rating_id',
            $foreignTable = $this->table('ratings'), 
            $foreignColumn = 'ID'
        );
        $this->addForeignConstraint(
            $table = $this->table('assigned_posts'),
            $constraint = $this->foreignConstraint('assigned_posts_post_id'),
            $foreignKey = 'post_id',
            $foreignTable = $this->db->posts, 
            $foreignColumn = 'ID'
        );
    }

    /**
     * @return void
     */
    public function addAssignedTermsForeignConstraints()
    {
        $this->addForeignConstraint(
            $table = $this->table('assigned_terms'),
            $constraint = $this->foreignConstraint('assigned_terms_rating_id'),
            $foreignKey = 'rating_id',
            $foreignTable = $this->table('ratings'), 
            $foreignColumn = 'ID'
        );
        $this->addForeignConstraint(
            $table = $this->table('assigned_terms'),
            $constraint = $this->foreignConstraint('assigned_terms_term_id'),
            $foreignKey = 'term_id',
            $foreignTable = $this->db->terms, 
            $foreignColumn = 'term_id'
        );
    }

    /**
     * @return void
     */
    public function addAssignedUsersForeignConstraints()
    {
        $this->addForeignConstraint(
            $table = $this->table('assigned_users'),
            $constraint = $this->foreignConstraint('assigned_users_rating_id'),
            $foreignKey = 'rating_id',
            $foreignTable = $this->table('ratings'), 
            $foreignColumn = 'ID'
        );
        $this->addForeignConstraint(
            $table = $this->table('assigned_users'),
            $constraint = $this->foreignConstraint('assigned_users_user_id'),
            $foreignKey = 'user_id',
            $foreignTable = $this->db->users, 
            $foreignColumn = 'ID'
        );
    }

    /**
     * @return void
     */
    public function addReviewsForeignConstraints()
    {
        $this->addForeignConstraint(
            $table = $this->table('ratings'),
            $constraint = $this->foreignConstraint('assigned_posts_review_id'),
            $foreignKey = 'review_id',
            $foreignTable = $this->db->posts, 
            $foreignColumn = 'ID'
        );
    }

    /**
     * This method expects the fully formed foreign constraint key
     * @param string $table
     * @param string $constraint
     * @param string $foreignKey
     * @param string $foreignTable
     * @param string $foreignColumn
     * @return int|bool
     * @see $this->foreignConstraint()
     */
    public function addForeignConstraint($table, $constraint, $foreignKey, $foreignTable, $foreignColumn)
    {
        if ($this->foreignConstraintExists($constraint, $foreignTable)) {
            return false;
        }
        return glsr(Database::class)->dbQuery(glsr(Query::class)->sql("
            ALTER TABLE {$table}
            ADD CONSTRAINT {$constraint}
            FOREIGN KEY ({$foreignKey})
            REFERENCES {$foreignTable} ({$foreignColumn})
            ON DELETE CASCADE
        "));
    }

    /**
     * @return void
     */
    public function addForeignConstraints()
    {
        if (!defined('GLSR_UNIT_TESTS')) {
            $this->addAssignedPostsForeignConstraints();
            $this->addAssignedTermsForeignConstraints();
            $this->addAssignedUsersForeignConstraints();
            $this->addReviewsForeignConstraints();
        }
    }

    /**
     * @return bool
     */
    public function createAssignedPostsTable()
    {
        if ($this->tableExists('assigned_posts')) {
            glsr_log()->debug(sprintf('Cannot create the %s table because it already exists.', $this->table('assigned_posts')));
            return false;
        }
        dbDelta(glsr(Query::class)->sql("
            CREATE TABLE {$this->table('assigned_posts')} (
                rating_id bigint(20) unsigned NOT NULL,
                post_id bigint(20) unsigned NOT NULL,
                is_published tinyint(1) NOT NULL DEFAULT '1',
                UNIQUE KEY {$this->prefix('assigned_posts_rating_id_post_id_unique')} (rating_id,post_id)
            ) ENGINE=InnoDB {$this->db->get_charset_collate()};
        "));
        return true;
    }

    /**
     * @return bool
     */
    public function createAssignedTermsTable()
    {
        if ($this->tableExists('assigned_terms')) {
            glsr_log()->debug(sprintf('Cannot create the %s table because it already exists.', $this->table('assigned_terms')));
            return false;
        }
        dbDelta(glsr(Query::class)->sql("
            CREATE TABLE {$this->table('assigned_terms')} (
                rating_id bigint(20) unsigned NOT NULL,
                term_id bigint(20) unsigned NOT NULL,
                UNIQUE KEY {$this->prefix('assigned_terms_rating_id_term_id_unique')} (rating_id,term_id)
            ) ENGINE=InnoDB {$this->db->get_charset_collate()};
        "));
        return true;
    }

    /**
     * @return bool
     */
    public function createAssignedUsersTable()
    {
        if ($this->tableExists('assigned_users')) {
            glsr_log()->debug(sprintf('Cannot create the %s table because it already exists.', $this->table('assigned_users')));
            return false;
        }
        dbDelta(glsr(Query::class)->sql("
            CREATE TABLE {$this->table('assigned_users')} (
                rating_id bigint(20) unsigned NOT NULL,
                user_id bigint(20) unsigned NOT NULL,
                UNIQUE KEY {$this->prefix('assigned_users_rating_id_user_id_unique')} (rating_id,user_id)
            ) ENGINE=InnoDB {$this->db->get_charset_collate()};
        "));
        return true;
    }

    /**
     * WordPress codex says there must be two spaces between PRIMARY KEY and the key definition.
     * @return bool
     * @see https://codex.wordpress.org/Creating_Tables_with_Plugins
     */
    public function createRatingTable()
    {
        if ($this->tableExists('ratings')) {
            glsr_log()->debug(sprintf('Cannot create the %s table because it already exists.', $this->table('ratings')));
            return false;
        }
        dbDelta(glsr(Query::class)->sql("
            CREATE TABLE {$this->table('ratings')} (
                ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                review_id bigint(20) unsigned NOT NULL,
                rating int(11) NOT NULL DEFAULT '0',
                type varchar(20) DEFAULT 'local',
                is_approved tinyint(1) NOT NULL DEFAULT '0',
                is_pinned tinyint(1) NOT NULL DEFAULT '0',
                name varchar(250) DEFAULT NULL,
                email varchar(100) DEFAULT NULL,
                avatar varchar(200) DEFAULT NULL,
                ip_address varchar(100) DEFAULT NULL,
                url varchar(250) DEFAULT NULL,
                PRIMARY KEY  (ID),
                UNIQUE KEY {$this->prefix('ratings_review_id_unique')} (review_id),
                KEY {$this->prefix('ratings_rating_type_is_pinned_index')} (rating,type,is_pinned)
            ) ENGINE=InnoDB {$this->db->get_charset_collate()};
        "));
        return true;
    }

    /**
     * @return void
     */
    public function createTables()
    {
        $this->createAssignedPostsTable();
        $this->createAssignedTermsTable();
        $this->createAssignedUsersTable();
        $this->createRatingTable();
        add_option(glsr()->prefix.'db_version', glsr()->version('minor'));
    }

    /**
     * @param string $table
     * @param string $constraint
     * @return int|bool
     */
    public function dropForeignConstraint($table, $constraint)
    {
        $table = $this->table($table);
        $constraint = $this->foreignConstraint($constraint);
        if (!$this->foreignConstraintExists($constraint)) {
            return false;
        }
        return glsr(Database::class)->dbQuery(glsr(Query::class)->sql("
            ALTER TABLE {$table} DROP FOREIGN KEY {$constraint};
        "));
    }

    /**
     * This method expects the fully formed foreign constraint key
     * @param string $constraint
     * @param string $foreignTable
     * @return bool
     * @see $this->foreignConstraint()
     */
    public function foreignConstraintExists($constraint, $foreignTable = '')
    {
        if (!empty($foreignTable) && !$this->isInnodb($foreignTable)) {
            glsr_log()->debug("Cannot check for a foreign constraint because {$foreignTable} does not use the InnoDB engine.");
            return true; // we cannot create foreign contraints on MyISAM tables
        }
        if (!is_array($this->constraints)) {
            $this->constraints = $this->db->get_col("
                SELECT CONSTRAINT_NAME
                FROM INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS
                WHERE CONSTRAINT_SCHEMA = '{$this->db->dbname}'
            ");
        }
        return in_array($constraint, $this->constraints);
    }

    /**
     * @param string $table
     * @return bool
     */
    public function isInnodb($table)
    {
        $engine = $this->db->get_var("
            SELECT ENGINE
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = '{$this->db->dbname}' AND TABLE_NAME = '{$this->table($table)}'
        ");
        if (empty($engine)) {
            glsr_log()->warning(sprintf('InnoDB: The %s database table does not exist.', $this->table($table)));
            return false;
        }
        return 'innodb' === strtolower($engine);
    }

    /**
     * @param string $table
     * @param string $prefix
     * @return string
     */
    public function prefix($table, $prefix = '')
    {
        $table = Str::prefix($table, glsr()->prefix);
        return Str::prefix($table, $prefix);
    }

    /**
     * @param string $constraint
     * @return string
     */
    public function foreignConstraint($constraint)
    {
        $constraint = Str::prefix($constraint, glsr()->prefix);
        $constraint = Str::suffix($constraint, '_foreign');
        if (is_multisite() && $this->db->blogid > 1) {
            return Str::suffix($constraint, '_'.$this->db->blogid);
        }
        return $constraint;
    }

    /**
     * @param string $table
     * @return string
     */
    public function table($table)
    {
        if (in_array($table, $this->db->tables())) {
            return $table; // WordPress table is already prefixed
        }
        // do this next in case another plugin has created a similar table
        if (Str::endsWith(['ratings', 'assigned_posts', 'assigned_terms', 'assigned_users'], $table)) {
            $table = Str::removePrefix($table, $this->db->get_blog_prefix());
            $table = Str::removePrefix($table, glsr()->prefix);
            return $this->prefix($table, $this->db->get_blog_prefix());
        }
        if (array_key_exists($table, $this->db->tables())) {
            return $this->db->{$table}; // the prefixed WordPress table
        }
        glsr_log()->error("The {$table} table does not exist.");
        return $table; // @todo maybe throw an exception here instead...
    }

    /**
     * @param string $table
     * @return bool
     */
    public function tableExists($table)
    {
        if (!is_array($this->tables)) {
            $prefix = $this->db->get_blog_prefix().glsr()->prefix;
            $this->tables = $this->db->get_col(
                $this->db->prepare("SHOW TABLES LIKE %s", $this->db->esc_like($prefix).'%')
            );
        }
        return in_array($this->table($table), $this->tables);
    }

    /**
     * @return string[]
     */
    public function tableEngines()
    {
        $results = $this->db->get_results("
            SELECT TABLE_NAME, ENGINE
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = '{$this->db->dbname}'
            AND TABLE_NAME IN ('{$this->db->options}','{$this->db->posts}','{$this->db->terms}','{$this->db->users}')
        ");
        $engines = [];
        foreach ($results as $result) {
            if (!array_key_exists($result->ENGINE, $engines)) {
                $engines[$result->ENGINE] = [];
            }
            $engines[$result->ENGINE][] = Str::removePrefix($result->TABLE_NAME, $this->db->get_blog_prefix());
        }
        $tableEngines = [];
        foreach ($engines as $engine => $tables) {
          $tableEngines[] = sprintf('%s (%s)', $engine, implode('|', $tables));
        }
        return $tableEngines;
    }
}
