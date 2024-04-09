<body style = "font_family: Courier">

# Lectures System Archive

## What is this project? 
<br>

> this project is supposed to be a filesystem, that includes  lectures from a lot of colleges.

## What Does it Contain?

it contains 3 ways of categorising: 
1. University --> Faculty (college) --> Specialty (if exist) --> Course --> Lectures
2. Faculty (of all Universities) --> University --> Course --> Lectures
3. Type --> Course --> Lectures

## Types of Users: 
* Normal Users
* Admins
* Super Admins

### What Can Normal Users Do?

* first, you need to create an account.
* then, you can: 
    1. Upload Lectures:
        - you should specify the:
            1. University 
            2. Faculty
            3. Specialty (if exist)
            4. Type (Medicine, Civil Engineering, etc.)
            5. Name of the Course (with the year if exists)
            6. name and number of the lecture (if exist).
        - and send the lecture as **PDF**.

    2. Show Trees:
        - tree of **Universities** 
        - tree of **Faculties of a University**.
        - tree of **Courses of a Faculty**.
        - tree of **Types**
        - tree of **Courses of a type**
 
    3. Show Lectures (they are the leaves of a tree).

    4. Report a Course or a Lecture for the admins.

### What Can Admins Do?
1. **Review** the Reports
2. **Delete** Lectures
3. **Send** the Review to the **User** who reported
4. **Send** a Report to ban a user to the **Super Admins**

### What Can Super Admins Do?
1. **Review** the reports from **Admins**
2. **Delete** Lectures
3. **Ban** a User

## Trees:
1. <br>
    <img src= "./res/Screenshot (11).png" width = 720px hight = 240px>
    <br>
2. <br>
    <img src = "./res/Screenshot (13).png" width = 720px hight = 240px>
   <br>
3. <br>
    <img src = "./res/Screenshot (15).png" width = 720px hight = 240px>
    <br>
## Application Tables
    [no Users]
<br>
    <img src = "./res/Screenshot (17).png">





</body>