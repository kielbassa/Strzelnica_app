-- Migration to integrate clients with user registration system
-- This script adds support for clients without membership and links users to clients

-- Step 1: Add user_id column to clients table to link with users table
ALTER TABLE `clients` ADD COLUMN `user_id` int(11) NULL AFTER `ID_client`;

-- Step 2: Allow NULL memberships for clients who haven't purchased membership yet
ALTER TABLE `clients` MODIFY `ID_membership` int(11) NULL;

-- Step 3: Create a "No Membership" entry in membership table for default assignment
INSERT INTO `membership` (`ID_membership`, `type`, `activation_date`, `expiration_date`) 
VALUES (999, 'No Membership', '1970-01-01', '1970-01-01');

-- Step 4: Add foreign key constraint for user_id
ALTER TABLE `clients` ADD CONSTRAINT `clients_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Step 5: Add unique constraint to ensure one client per user
ALTER TABLE `clients` ADD UNIQUE KEY `unique_user_client` (`user_id`);

-- Step 6: Update existing clients to have NULL membership for demonstration
-- (In real scenario, you might want to keep existing memberships)
-- UPDATE `clients` SET `ID_membership` = NULL WHERE `ID_membership` IS NOT NULL;

-- Step 7: Create index for better performance
CREATE INDEX `idx_clients_user_id` ON `clients` (`user_id`);
CREATE INDEX `idx_clients_membership` ON `clients` (`ID_membership`);

-- Verification queries (uncomment to test):
-- SELECT 'Users table structure:' as info;
-- DESCRIBE users;
-- SELECT 'Clients table structure:' as info;
-- DESCRIBE clients;
-- SELECT 'Sample data check:' as info;
-- SELECT u.id, u.first_name, u.last_name, u.email, c.ID_client, c.name, c.surname, c.ID_membership 
-- FROM users u 
-- LEFT JOIN clients c ON u.id = c.user_id 
-- LIMIT 5;