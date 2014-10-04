# Database Design for Idea Website

## Tables 

### user_table
<table>
	<tr>
		<td>user_id (PK)</td><td>name</td><td>email</td><td>FB ID</td>
	</tr>
</table>

### idea_table
<table>
	<tr>
		<td>idea_id (PK)</td><td>user_id(FK)</td><td>idea_text</td><td>date_posted</td><td>date_modified</td><td>votes</td>
	</tr>
</table>

### comments_table
<table>
	<tr>
		<td>comment_id (PK)</td><td>user_id (FK)</td><td>idea_ID (FK)</td><td>comment_text</td><td>date_posted</td><td>date_modified</td>
	</tr>
</table>

### voting_table - This ensures that a user can only one cast one vote per idea 
<table>
	<tr>
		<td>idea_id (FK)</td><td>user_id(FK)</td>
	</tr>
</table>
