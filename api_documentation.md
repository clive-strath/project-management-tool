# API Documentation - V1

All API endpoints are prefixed with `/api/v1`.

## Authentication
- `POST /api/v1/register`: Register a new user.
- `POST /api/v1/login`: Login and receive a Sanctum token.
- `POST /api/v1/logout`: Revoke the current token.
- `GET /api/v1/me`: Get the current authenticated user details.

## Projects
- `GET /api/v1/projects`: List all projects accessible to the user.
- `POST /api/v1/projects`: Create a new project.
- `GET /api/v1/projects/{id}`: Get project details (with boards and members).
- `PUT /api/v1/projects/{id}`: Update project details.
- `DELETE /api/v1/projects/{id}`: Delete a project.

## Boards
- `GET /api/v1/projects/{project}/boards`: List boards for a project.
- `POST /api/v1/projects/{project}/boards`: Create a board.
- `GET /api/v1/boards/{id}`: Get board details (with lists and tasks).
- `PUT /api/v1/boards/{id}`: Update board.
- `DELETE /api/v1/boards/{id}`: Delete board.

## Tasks
- `GET /api/v1/task-lists/{list}/tasks`: List tasks in a list.
- `POST /api/v1/task-lists/{list}/tasks`: Create a task.
- `GET /api/v1/tasks/{id}`: Get task details.
- `PUT /api/v1/tasks/{id}`: Update task (can move between lists).
- `DELETE /api/v1/tasks/{id}`: Delete task.

## Comments & Attachments
- `GET /api/v1/tasks/{task}/comments`: List comments.
- `POST /api/v1/tasks/{task}/comments`: Add a comment.
- `POST /api/v1/tasks/{task}/attachments`: Upload a file.
- `GET /api/v1/attachments/{attachment}/download`: Download file.

## Time Tracking
- `GET /api/v1/tasks/{task}/time-entries`: List time entries.
- `POST /api/v1/tasks/{task}/time-entries`: Start/Add time entry.
- `PATCH /api/v1/time-entries/{id}`: Update/Stop time entry.

## Reports & Activity
- `GET /api/v1/projects/{project}/activity`: Project-wide activity log.
- `GET /api/v1/projects/{project}/reports/summary`: Project time summary.
- `GET /api/v1/tasks/{task}/reports/summary`: Task time summary.
