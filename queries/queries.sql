/* Максимальная зп*/
SELECT a.*, e.name, e.email 
FROM accounter_data a
JOIN employee e ON e.id = a.employee_id
WHERE a.salary_per_month = (SELECT MAX(salary_per_month) FROM accounter_data);

/*Второй человек получающий самую большую ЗП*/	
SELECT a.*, e.name, e.email 
FROM accounter_data a
JOIN employee e ON e.id = a.employee_id
WHERE a.salary_per_month = (
	SELECT MAX(tmp.salary_per_month) FROM (
		SELECT salary_per_month FROM accounter_data WHERE salary_per_month != (
			SELECT MAX(salary_per_month) FROM accounter_data
			)
		) as tmp
	);


/*Сотрудник с максимальной зп за 3 последних месяца*/
SELECT DISTINCT a.*, e.name, e.email, m.payment, b.amount as bonus, pd.`date` 
FROM accounter_data a
JOIN employee e ON e.id = a.employee_id
JOIN month_payment m ON m.employee_id = e.id
JOIN bonuses_penalties b ON b.employee_id = e.id
JOIN payment_day pd ON pd.id = m.payment_day_id
WHERE b.payment_day_id = m.payment_day_id AND m.payment = (
	SELECT MAX(m1.payment) 
	FROM month_payment m1 
	JOIN payment_day p ON p.id = m1.payment_day_id 
	WHERE MONTH(p.`date`) > MONTH(NOW() - INTERVAL 3 MONTH) AND MONTH(p.`date`) <= MONTH(NOW()) 
);

/*Общая сумма выплат за предыдущий месяц*/
SELECT SUM(m.payment) 
FROM month_payment m
JOIN payment_day pd ON pd.id = m.payment_day_id
WHERE MONTH(pd.`date`) = MONTH(NOW());
