# Blog Demo — Symfony BlogBundle

A reusable Symfony bundle that implements blog post CRUD, plus a minimal demo application that installs and exercises the bundle.

## Requirements

- PHP 8.1+
- Composer 2
- Symfony 7.2 (demo app)
- SQLite (default) or any Doctrine-supported database

## Project structure

```
blog-demo/
├── blog-bundle/          # Reusable BlogBundle (installable via Composer path/repo)
└── webapp/               # Demo Symfony application
```

### Bundle highlights

- **Entity:** `Post` with id, title, content, slug, status (`draft` / `published`), timestamps
- **Layers:** `PostRepository`, `PostService` (+ interfaces), `SlugGenerator`, thin `PostController`
- **UI:** Twig forms with CSRF protection and auto-escaping (XSS-safe output)
- **Extras:** pagination, filter by title/status, validation constraints, Doctrine migration

## Quick start

```bash
cd webapp
composer install
cp ../.env.example .env   # or edit .env directly
# Set APP_SECRET in .env

php bin/console doctrine:migrations:migrate --no-interaction
symfony server:start -d   # or: php -S 127.0.0.1:8000 -t public
```

Open [http://127.0.0.1:8000/blog/](http://127.0.0.1:8000/blog/) (or the URL shown by the Symfony CLI).

## Configuration

In `webapp/config/packages/blog.yaml` (create if needed):

```yaml
blog:
    route_prefix: '/blog'   # URL prefix for bundle routes
    posts_per_page: 10      # pagination size (1–100)
```

The demo app imports bundle routes in `webapp/config/routes.yaml`:

```yaml
blog_bundle:
    resource: '@BlogBundle/src/Resources/config/routes.yaml'
```

## Installing the bundle in another app

1. Add a Composer path or VCS repository pointing to `blog-bundle/`.
2. `composer require demo/blog-bundle`.
3. Register `Demo\BlogBundle\BlogBundle` in `config/bundles.php`.
4. Import routes (see above).
5. Configure `blog:` settings.
6. Add the migrations path in `config/packages/doctrine_migrations.yaml`:

```yaml
doctrine_migrations:
    migrations_paths:
        "Demo\\BlogBundle\\Migrations": '%kernel.project_dir%/vendor/demo/blog-bundle/migrations'
```

7. Run `php bin/console doctrine:migrations:migrate`.

## Routes

| Method | Path | Name | Action |
|--------|------|------|--------|
| GET | `/blog/` | `blog_post_index` | List (paginated, filterable) |
| GET | `/blog/new` | `blog_post_new` | Create form |
| POST | `/blog/new` | `blog_post_new` | Create |
| GET | `/blog/{id}` | `blog_post_show` | Read |
| GET/POST | `/blog/{id}/edit` | `blog_post_edit` | Update |
| POST | `/blog/{id}/delete` | `blog_post_delete` | Delete |

## Tests

```bash
cd webapp
php bin/phpunit
```

Includes unit tests for `SlugGenerator` and functional CRUD/filter tests against SQLite.

## Architecture notes

- Business logic lives in `PostService`, not the controller (SOLID / SRP).
- `PostServiceInterface` and `SlugGeneratorInterface` allow swapping implementations.
- Slugs are generated from the title and deduplicated (`hello-world`, `hello-world-2`, …).
- Forms use Symfony Validator constraints on the entity; the service validates before persist.

## License

MIT
