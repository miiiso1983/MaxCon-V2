# Database Setup for Production

## Problem
The purchasing tables (suppliers, supplier_contracts, etc.) are missing in the production MySQL database, causing the error:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'rrpkfnxwgn.supplier_contracts' doesn't exist
```

## Solutions

### Option 1: Run Laravel Migrations (Recommended)
```bash
# SSH into your production server
ssh your-server

# Navigate to your Laravel project
cd /path/to/your/laravel/project

# Run the migrations
php artisan migrate

# Check migration status
php artisan migrate:status
```

### Option 2: Execute SQL Script Directly
If migrations don't work, you can run the SQL script directly:

1. **Download the SQL file:**
   ```bash
   # Copy the create_purchasing_tables.sql file to your server
   scp database/sql/create_purchasing_tables.sql your-server:/tmp/
   ```

2. **Execute in MySQL:**
   ```bash
   # Connect to MySQL
   mysql -u your_username -p your_database_name

   # Run the script
   source /tmp/create_purchasing_tables.sql;

   # Or run it directly
   mysql -u your_username -p your_database_name < /tmp/create_purchasing_tables.sql
   ```

3. **Verify tables were created:**
   ```sql
   SHOW TABLES LIKE '%supplier%';
   SHOW TABLES LIKE '%purchase%';
   SHOW TABLES LIKE '%quotation%';
   ```

### Option 3: Manual Table Creation
Copy and paste the SQL commands from `create_purchasing_tables.sql` directly into your MySQL console.

## Tables Created

1. **suppliers** - Supplier management
2. **supplier_contracts** - Contract management
3. **purchase_requests** - Purchase request workflow
4. **quotations** - Quotation management

## Sample Data
The SQL file includes commented sample data. Uncomment the INSERT statements at the bottom if you want to add test data.

## Verification
After running the script, verify the contracts page works:
- Visit: https://www.maxcon.app/tenant/purchasing/contracts
- Should show the contracts interface without errors

## Troubleshooting

### Foreign Key Errors
If you get foreign key constraint errors, make sure these tables exist first:
- `tenants`
- `users`

### Permission Errors
Make sure your MySQL user has CREATE, ALTER, and INSERT permissions.

### Character Set Issues
The tables use `utf8mb4` character set for proper Arabic support.

## Contact
If you continue to have issues, check the Laravel logs:
```bash
tail -f storage/logs/laravel.log
```
