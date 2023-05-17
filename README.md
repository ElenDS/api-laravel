I am pleased to present my Laravel-based API project.
The API provide the functionality to create users, associate multiple projects with multiple users and multiple labels with multiple projects.

# What inside
**Laravel**

**MySQL**

**Docker Compose**

# How to use

Create Users
```
POST /api/users

{
    "users": [
        {
            "username": "name1",
            "email": "user_email1",
            "country_code": "UA"
        },
        {
            "username": "name2",
            "email": "user_email2",
            "country_code": "BV"
        }
    ]
}
```

Update Users
```
PUT /api/users

{
    "users": [
        {
            "username": "name1",
            "email": "user_email1",
            "country_code": "UA"
        },
        {
            "username": "name2",
            "email": "user_email2",
            "country_code": "WF"
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Delete Users
```
DELETE /api/users

{
    "users": [
        {
            "email": "user_email1"
        },
        {
            "email": "user_email2"
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Get List Of Users
```
GET /api/users

{
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Create Labels
```
POST /api/labels

{
    "labels": [
        {
            "name": "label1"
        },
        {
            "name": "label2"
        },
        {
            "name": "label3"
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Link Labels To Projects
```
PUT /api/link-labels

{
    "labels": [
        {
            "name": "label1",
            "projects": [
                {
                    "name": "project1"
                },
                {
                    "name": "project2"
                }
            ]
        },
        {
            "name": "label2",
            "projects": [
                {
                    "name": "project1"
                }
            ]
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Delete Labels
```
DELETE /api/labels

{
    "labels": [
        {
            "name": "label1"
        },
        {
            "name": "label2"
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Get List Of Labels
```
GET /api/labels

{
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Create Projects
```
POST /api/projects

{
    "projects": [
        {
            "name": "project1",
            "members": [
                    {
                        "email": "email1"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        },
       {
            "name": "project2",
            "members": [
                    {
                        "email": "email2"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Update Projects
```
PUT /api/projects

{
    "projects": [
        {
            "name": "project1",
            "members": [
                    {
                        "email": "email1"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        },
       {
            "name": "project2",
            "members": [
                    {
                        "email": "email2"
                    }
                ],
            "labels": [
                {
                    "name": "label1"
                },
                {
                    "name": "label2"
                }
            ]
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Delete Projects
```
DELETE /api/projects

{
    "projects": [
        {
            "name": "project99"
        },
        {
            "name": "project999"
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Link Projects To Users
```
PUT /api/link-projects

{
    "projects": [
        {
            "name": "project1",
            "users": [
                {
                "email": "user_email1"
                }
            ]
        },
        {
            "name": "project2",
            "users": [
                {
                "email": "user_email1"
                }
            ]
        }
    ],
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```
Get List Of Projects
```
GET /api/projects

{
    "email": "email_for_authorization",
    "token": "token_for_athorization"
}
```



