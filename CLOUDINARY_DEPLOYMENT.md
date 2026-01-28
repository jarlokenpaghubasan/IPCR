# Cloudinary Integration - Deployment Instructions

## ✅ What's Been Integrated

The `temp-deployment` branch now includes:
- ✅ Cloudinary photo upload service
- ✅ Automatic image optimization (resize, quality, format)
- ✅ Database migration for `cloudinary_public_id` column
- ✅ Updated User model to recognize Cloudinary URLs
- ✅ Cloudinary Laravel package installed

## 🔐 Environment Variables - Where to Set Them

**IMPORTANT:** The `.env` file is NOT committed to this branch to protect your secrets. You must manually add these environment variables in your deployment environment.

### Option 1: Direct `.env` File (Local Development)
Add these lines to your `.env` file in the project root:

```env
CLOUDINARY_CLOUD_NAME=dntjrz3mi
CLOUDINARY_API_KEY=666187645511746
CLOUDINARY_API_SECRET=0b0aCaMlUAvBoiBU_5srXLVD16o
CLOUDINARY_URL=cloudinary://666187645511746:0b0aCaMlUAvBoiBU_5srXLVD16o@dntjrz3mi
```

### Option 2: Server Environment Variables (Production - Recommended)

Set these as environment variables on your hosting/server:

**Heroku:**
```bash
heroku config:set CLOUDINARY_CLOUD_NAME=dntjrz3mi
heroku config:set CLOUDINARY_API_KEY=666187645511746
heroku config:set CLOUDINARY_API_SECRET=0b0aCaMlUAvBoiBU_5srXLVD16o
heroku config:set CLOUDINARY_URL=cloudinary://666187645511746:0b0aCaMlUAvBoiBU_5srXLVD16o@dntjrz3mi
```

**cPanel/Shared Hosting:**
1. Log in to cPanel
2. Go to: **Software** → **Env Variables** or **System PHP Info**
3. Add each variable manually
4. Or edit `.htaccess` with:
```apache
SetEnv CLOUDINARY_CLOUD_NAME dntjrz3mi
SetEnv CLOUDINARY_API_KEY 666187645511746
SetEnv CLOUDINARY_API_SECRET 0b0aCaMlUAvBoiBU_5srXLVD16o
SetEnv CLOUDINARY_URL cloudinary://666187645511746:0b0aCaMlUAvBoiBU_5srXLVD16o@dntjrz3mi
```

**Docker:**
Add to your `docker-compose.yml`:
```yaml
environment:
  - CLOUDINARY_CLOUD_NAME=dntjrz3mi
  - CLOUDINARY_API_KEY=666187645511746
  - CLOUDINARY_API_SECRET=0b0aCaMlUAvBoiBU_5srXLVD16o
  - CLOUDINARY_URL=cloudinary://666187645511746:0b0aCaMlUAvBoiBU_5srXLVD16o@dntjrz3mi
```

**AWS/DigitalOcean/Laravel Forge:**
Use the hosting platform's environment variable management panel to add each variable.

### Option 3: `.env.example` Template (For Team)

If you want to document what env vars are needed without exposing secrets:

1. Create/update `.env.example`:
```env
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_URL=cloudinary://YOUR_API_KEY:YOUR_API_SECRET@YOUR_CLOUD_NAME
```

2. Tell team members: "Copy the Cloudinary credentials into these variables in your `.env` file"

## 🚀 Deployment Checklist

- [ ] Pull the `temp-deployment` branch
- [ ] Run `composer install` (if dependencies changed)
- [ ] Run `npm install && npm run build` (rebuild assets)
- [ ] Set Cloudinary env vars in your deployment environment
- [ ] Run `php artisan migrate` (new cloudinary_public_id column)
- [ ] Test photo upload
- [ ] Verify photos display correctly

## 📝 Files Changed

- `app/Services/PhotoService.php` - Upload/delete to Cloudinary
- `app/Models/User.php` - Recognize Cloudinary photo URLs
- `app/Models/UserPhoto.php` - Added cloudinary_public_id field
- `config/cloudinary.php` - Cloudinary configuration
- `database/migrations/2026_01_29_001504_add_cloudinary_public_id_to_user_photos_table.php` - Database migration
- `composer.json` / `composer.lock` - Added cloudinary-labs/cloudinary-laravel package

## ❌ What's NOT Committed

- `.env` file (contains API secrets) - You must add manually
- `node_modules/` - Run `npm install` after pulling

## 🔄 How It Works

1. **User uploads photo** → Sent to Cloudinary
2. **Cloudinary stores it** → Returns secure HTTPS URL + public_id
3. **System stores URL** → Saved in database for quick retrieval
4. **Photo displays** → Uses Cloudinary URL directly (super fast CDN!)
5. **Delete photo** → Removes from Cloudinary + database

## 🆘 Troubleshooting

**Upload fails - "Invalid Cloudinary response":**
- Check that env vars are set correctly
- Verify API Key and Secret are accurate
- Check Cloud Name matches your Cloudinary account

**Photo shows 403 Forbidden:**
- The path was stored as local path instead of Cloudinary URL
- This happens if credentials weren't set during upload
- Solution: Re-upload the photo after setting env vars

**Photos don't appear after migration:**
- Old photos were stored locally, new ones on Cloudinary
- Both storage methods are supported
- Local photos still work if file exists

