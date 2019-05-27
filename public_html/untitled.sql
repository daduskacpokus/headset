//выбрать весь приход
SELECT `row_id`, `row_date`, `increment_id`, `increment_label`, 
	`increment_condition`, `increment_storage` 
FROM `across` 
WHERE `decrement_id` IS NULL 
AND `rotate` = FALSE 
AND `reverse_date` IS NULL;

//выбрать весь расход
SELECT `row_id`, `row_date`, `decrement_id`, `decrement_label`, 
	`decrement_condition`, `decrement_storage` 
FROM `across` 
WHERE `increment_id` IS NULL 
AND `rotate` = FALSE 
AND `reverse_date` IS NULL;


