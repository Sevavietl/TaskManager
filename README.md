# Task manager

I'm a person who passionate about my own productivity. I want to manage my tasks and projects more effectively. I need a simple tool that supports me in controlling my task flow.

Functional requirements
------

&#9733; I want to be able to create/update/delete projects &#10004;

&#9733; I want to be able to add tasks to my project &#10004;

&#9733; I want to be able to update/delete tasks &#10004;

&#9733; I want to be able to prioritize tasks into a project &#10004;

&#9733; I want to be able to choose deadline for my task &#10004;

&#9733; I want to be able to mark a task as 'done' &#10004;

Technical requirements
------

&#9733; It should be a WEB application &#10004;

&#9733; For the client side must be used: HTML, CSS (any libs as Twitter Bootstrap, Blueprint ...), JavaScript (any libs as jQuery, Prototype ...) &#10004; **(Vue.js + Bootstrap 3)**

&#9733; For a server side any language as Ruby, PHP, Python, JavaScript, C#, Java ... &#10004; **(Laravel)**

&#9733; It should have a client side and server side validation &#10008; **(Only server side validation)**

&#9733; It should look like on screens (see attached file 'rg_test_task_grid.png'). &#10008; **(General resemblance only)**

Technical requirements
------

&#9733; It should work like one page WEB application and should use AJAX technology, load and submit data without reloading a page. &#10008; **(Not completely a SPA)**

&#9733; It should have user authentication solution and a user should only have accesss to their own projects and tasks. &#10004;

&#9733; It should have automated tests for the all functionality &#10008; **(Only server side functionality covered with tests)**

# SQL task

Given tables:
------

&#9733; tasks (id, name, status, project_id)

&#9733; projects (id, name)

Write the queries for:
------

1. get all statuses, not repeteating, alphabetically ordered

```sql
SELECT DISTINCT `status`
FROM `tasks`
ORDER BY `status` ASC
```

2. get the count of all tasks in each project, order by tasks count descending

```sql
SELECT `project_id`, COUNT(`id`) `count_of_tasks` 
FROM `tasks` 
GROUP BY `project_id` 
ORDER BY `count_of_tasks` DESC
```

3. get the count of all tasks in each project, order by projects names

```sql
SELECT `p`.`name`, COUNT(`t`.`id`) `count_of_tasks` 
FROM `projects` `p`
INNER JOIN `tasks` `t` ON `p`.`id` = `t`.`project_id`
GROUP BY `t`.`project_id`
ORDER BY `p`.`name` ASC
```

4. get the tasks for all projects having the name beginning with "N" letter

```sql
SELECT *
FROM `tasks`
WHERE `name` LIKE 'N%'
```

5. get the list of all projects containing the 'a' letter in the middle of the name, and show the tasks count near each project. Mention that there can exist projects without tasks and tasks with project_id=NULL

```sql
SELECT `p`.*, COUNT(`t`.`id`) `count_of_tasks` 
FROM `projects` `p`
INNER JOIN `tasks` `t` ON `p`.`id` = `t`.`project_id`
WHERE `t`.`name` LIKE '%a%'
GROUP BY `t`.`project_id`
```

6. get the list of tasks with duplicate names. Order alphabetically

```sql
SELECT * 
FROM `tasks`  
INNER JOIN(
    SELECT `name`  
    FROM `tasks`  
    GROUP BY `name`  
    HAVING COUNT(`name`) > 1  
) `temp` ON `tasks`.`name` = `temp`.`name`
ORDER BY `tasks`.`name` ASC
```

7. get the list of tasks having several exact matches of both name and status, from the project 'Garage'. Order by matches count

```sql
SELECT `t`.`name`, `t`.`status`, COUNT(`t`.`name`) `c`
FROM `tasks` `t`
INNER JOIN `projects` `p` ON `t`.`project_id` = `p`.`id`
INNER JOIN (
	SELECT CONCAT(`name`, `status`) `ns`
    FROM `tasks`
    GROUP BY `ns`
    HAVING COUNT(`ns`) > 1
) `temp` ON CONCAT(`t`.`name`, `t`.status) = `temp`.`ns`
WHERE `p`.`name` = 'Garage'
GROUP BY `t`.`name`, `t`.`status`
ORDER BY `c` ASC
```

8. get the list of project names having more than 10 tasks in status 'completed'. Order by project_id

```sql
SELECT `p`.`name`
FROM `projects` `p`
INNER JOIN (
	SELECT `project_id`
    FROM `tasks`
    GROUP BY `project_id`
    WHERE `status` = 'completed'
    HAVING COUNT(`id`) > 10
) `t` ON `t`.`project_id` = `p`.`id`
ORDER BY `p`.`id` ASC
```
