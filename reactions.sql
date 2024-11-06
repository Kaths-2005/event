-- Use the eventify database
USE eventify;

-- Create the reactions table
CREATE TABLE reactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,                -- ID for each event, e.g., 1 for "DJ x Garba Night"
    reaction_type VARCHAR(10) NOT NULL,    -- Reaction type, e.g., "heart", "thumbsup"
    count INT DEFAULT 0                    -- The count of each reaction
);