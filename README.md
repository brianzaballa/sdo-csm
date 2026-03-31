# SDO Legazpi City — Client Satisfaction Measurement (CSM)

A public-facing survey application for collecting client satisfaction feedback on government office transactions, built for the Schools Division Office of Legazpi City.

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 |
| UI | Livewire 4.2 |
| Styling | Tailwind CSS v4 |
| Bundler | Vite |
| Database | SQLite (dev) / MySQL (prod) |
| PHP | 8.3+ |

## Features

- **4-step survey form** — Client Info, Citizen's Charter, Service Quality Dimension, Suggestions
- **Conditional logic** — CC2/CC3 questions show/hide based on CC1 answer
- **Server-side validation** — Each step validates before proceeding
- **Emoji-based ratings** — SQD questions use a 6-point Likert scale with emoji indicators
- **Responsive design** — Mobile-first layout using Tailwind CSS
- **Save for later** — Clients can save incomplete responses and return later

## Setup

```bash
# Clone and install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Build assets (required — without this you get ViteManifestNotFoundException)
npm run build

# Or run dev server for live reloading
npm run dev
```

## Usage

- **Survey form:** `http://sdo-csm.test/survey`
- **Dev mode:** Run `npm run dev` in a separate terminal for hot reload

## Project Structure

```
app/
  Livewire/
    Survey.php              # Multi-step survey component
  Models/
    Office.php              # Government offices
    Service.php             # Services per office
    SurveyResponse.php      # Survey submissions
database/
  seeders/
    OfficeSeeder.php        # 23 offices + 91 services
resources/
  views/
    livewire/
      survey.blade.php      # Survey form UI
    layouts/
      app.blade.php         # Main layout
routes/
  web.php                   # Routes (/ and /survey)
```

## Artisan Commands

```bash
# Re-seed offices and services
php artisan db:seed --class=OfficeSeeder

# Fresh start (drops all data)
php artisan migrate:fresh --seed

# Run tests
php artisan test
```

## License

Proprietary — SDO Legazpi City.
