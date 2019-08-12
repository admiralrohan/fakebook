# Use cases

Books have their own details, have ratings (with or without description) given by other users who have completed the book only
User can mark books as "want to read", "currently reading", "read" and "favourites"
Books can be included into variaous lists
One book can have one or multiple authors

# Entity (initial analysis)

Book => id, title, author (Can be multiple), description, ratings, reviews,
stats (added, ratings, reviews, to-read => chart+table), link (Get a copy from amazon), ISBN
User => id, fname, lname, location, joined_on, last_activity (update every time user do some action),

User actions => wants to read, rated a book, following, read

Book, User, Rating

# Views

User page => Name, Ratings given and avg, Reviews, Books written, Books read / want to read
Book page => Title, authors, avg rating, reviews, description, no of pages, Show reviews and ratings
Login, Registration => Needed when trying to rate a book, also encourage users to signup in every page when they are not logged in
