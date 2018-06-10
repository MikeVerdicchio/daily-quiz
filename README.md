Daily Quiz
==========
The Daily Quiz Game is a simple quiz - it asks you questions from a number of topics and saves your score. Each day the questions are randomly chosen from a pool (quiz-questions.txt).

This web application uses PHP, AJAX (JQuery), and MySQL. The Docker image contains everything necessary to run the application, including an Apache web server.

This is an assignment from the Programming Languages for Web Apps (CS 4501) special course at the University of Virginia. **If you are a current student in, or are planning to take, this version of CS 4501, it is an Honor Violation to view the code in this repository.**

```
git clone
sudo chown 755 -R daily-quiz
cd daily-quiz
docker-compose up -d db
docker-compose up web
```