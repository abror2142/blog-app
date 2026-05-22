# Blog App — Symfony BlogBundle

A reusable Symfony bundle that implements blog post CRUD, plus a minimal demo application that installs and exercises the bundle.

## Requirements

- PHP 8.1+
- Composer 2
- Symfony 6.4 (demo app)
- SQLite (default) or any Doctrine-supported database

## Project structure

```
blog-app/

├── webapp/                     # Symfony application
│   ├── bundle/
│   │   └── WebDev/
│   │       └── BlogBundle/             # Reusable BlogBundle
│   ├── config/
│   ├── src/
│   ├── templates/
│   ├── composer.json
│   └── ...
```

### Bundle highlights

- **Entity:** `Blog` with id, title, content, slug, status (`draft` / `published`), timestamps
- **Layers:** `BlogRepository`, `BlogService` (+ interfaces), `SlugGenerator`, thin `BlogController`
- **UI:** Twig forms
- **Extras:** pagination, filter by title/status, validation constraints, Doctrine migration

## Quick start

### For Instant installation, run this

```bash
git clone https://github.com/abror2142/blog-app.git
cd blog-app
cp .env.example .env
# Set APP_SECRET in .env
composer install

php bin/console doctrine:migrations:migrate --no-interaction
symfony server:start -d
```

Open [http://127.0.0.1:8000/blog/](http://127.0.0.1:8000/blog/) (or the URL shown by the Symfony CLI).

## Configuration

In `blog-app/config/packages/blog.yaml` (create if needed):

```yaml
blog:
    route_prefix: '/blog'   # URL prefix for bundle routes
    posts_per_page: 10      # pagination size (1–100)
```

The demo app imports bundle routes in `blog-app/config/routes.yaml`:

```yaml
blog_bundle:
    resource: '@BlogBundle/src/Resources/config/routes.yaml'
```

## Installing the bundle in another app

1. Add a Composer path or VCS repository pointing to `blog-bundle/`.
2. `composer require web-dev/blog-bundle`.
3. Register `WebDev\BlogBundle\WebDevBlogBundle` in `config/bundles.php`.
4. Import routes (see above).
5. Configure `blog:` settings.
7. Run `php bin/console doctrine:migrations:migrate`.

## Routes

| Method | Path | Name | Action |
|--------|------|------|--------|
| GET | `/blog/` | `blog_index` | List (paginated, filterable) |
| GET | `/blog/new` | `blog_new` | Create form |
| POST | `/blog/new` | `blog_new` | Create |
| GET | `/blog/{slug}` | `blog_show` | Read |
| GET/POST | `/blog/{slug}/edit` | `blog_edit` | Update |
| POST | `/blog/{slug}/delete` | `blog_delete` | Delete |


## Architecture notes

- Business logic lives in `BlogService`.
- `BlogServiceInterface` and `SlugGeneratorInterface` allow swapping implementations.
- Slugs are generated from the title and deduplicated (`hello-world`, `hello-world-2`, …).
- Forms use Symfony Validator constraints on the entity; the service validates before persist.

## License

MIT
