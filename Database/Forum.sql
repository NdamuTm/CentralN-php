-- Create the database
DROP DATABASE IF EXISTS centralN;
CREATE DATABASE centralN;
USE centralN;

-- Create Users Table
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    gender VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    contact VARCHAR(20) NOT NULL,
    photo VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Create Posts Table
CREATE TABLE Posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    tags VARCHAR(255),
    content TEXT NOT NULL,
    likes INT DEFAULT 0,
    comments_count INT DEFAULT 0,
    image VARCHAR(1000),
    user_id INT,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Create Events Table
CREATE TABLE Events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    event_info TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Likes Table

CREATE TABLE likes (
    like_id int NOT NULL  AUTO_INCREMENT primary key,
    post_id int NULL,
    user_id int NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp

);

-- Create Videos Table
CREATE TABLE Videos (
    video_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author_id INT,
    thumbnail VARCHAR(255),
    video_link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES Users(user_id)
);

-- Create Notifications Table
CREATE TABLE Notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Create Comments Table
CREATE TABLE Comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    content TEXT NOT NULL,
    parent_comment_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES Posts(post_id),
    FOREIGN KEY (parent_comment_id) REFERENCES Comments(comment_id)
);

-- Create status table
CREATE TABLE UserStatus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
-- Create friends table
CREATE TABLE Friends (
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    status ENUM('pending', 'accepted') DEFAULT 'pending',
    PRIMARY KEY (user_id, friend_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (friend_id) REFERENCES Users(user_id)
);

-- Insert sample data into the Tables




-- Insert Users
INSERT INTO Users (name, surname, gender, dob, email, contact, password, photo) VALUES
    ('Thabo', 'Mokoena', 'Male', '1990-01-01', 'thabo.m@gmail.com', '0712345678', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Naledi', 'Sibanda', 'Female', '1992-02-02', 'naledi.s@gmail.com', '0712345679', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Lerato', 'Nkosi', 'Female', '1995-03-03', 'lerato.nkosi@gmail.com', '0712345680', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Sibusiso', 'Dlamini', 'Male', '1988-04-04', 'sibusiso.dlamini@gmail.com', '0712345681', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Ayanda', 'Zulu', 'Female', '1993-05-05', 'ayanda.z@gmail.com', '0712345682', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Zanele', 'Ndlovu', 'Female', '1991-06-06', 'zanele.nd@gmail.com', '0712345683', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Kabelo', 'Mokoena', 'Male', '1989-07-07', 'kabelo.mokoena@gmail.com', '0712345684', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Mpho', 'Seabi', 'Male', '1994-08-08', 'mpho.s@gmail.com', '0712345685', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Khaya', 'Zwane', 'Male', '1987-09-09', 'khaya.zwane@gmail.com', '0712345686', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Dineo', 'Masilela', 'Female', '1996-10-10', 'dineo.m@gmail.com', '0712345687', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Sipho', 'Mhlongo', 'Male', '1986-11-11', 'sipho.mhlongo@gmail.com', '0712345688', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Nosipho', 'Khumalo', 'Female', '1991-12-12', 'nosipho.k@gmail.com', '0712345689', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Jabulani', 'Nxumalo', 'Male', '1990-01-13', 'jabulani.n@gmail.com', '0712345690', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Phindile', 'Sithole', 'Female', '1992-02-14', 'phindile.s@gmail.com', '0712345691', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Thandiwe', 'Mthembu', 'Female', '1988-03-15', 'thandiwe.m@gmail.com', '0712345692', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Palesa', 'Mokwena', 'Female', '1993-04-16', 'palesa.m@gmail.com', '0712345693', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Lindiwe', 'Tshabalala', 'Female', '1994-05-17', 'lindiwe.t@gmail.com', '0712345694', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Vusi', 'Maseko', 'Male', '1985-06-18', 'vusi.m@gmail.com', '0712345695', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Bongani', 'Nkosi', 'Male', '1991-07-19', 'bongani.nkosi@gmail.com', '0712345696', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Tshepo', 'Moloi', 'Male', '1990-08-20', 'tshepo.moloi@gmail.com', '0712345697', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Nandi', 'Mahlangu', 'Female', '1992-09-21', 'nandi.mahlangu@gmail.com', '0712345698', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Zodwa', 'Madonsela', 'Female', '1989-10-22', 'zodwa.m@gmail.com', '0712345699', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png'),
    ('Thembi', 'Radebe', 'Female', '1993-11-23', 'thembi.radebe@gmail.com', '0712345700', 'Password123', 'https://ndamutm.alurase.co.za/assets/images/my-avatar.png');


-- Insert Events

INSERT INTO Events (title, date, time, location, image, event_info) VALUES
                                                                        ('Eduvos Coding Hackathon', '2024-09-15', '10:00:00', 'Eduvos Sandton Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Join us for an exciting hackathon focused on innovation and coding.'),
                                                                        ('Eduvos Business Seminar', '2024-09-10', '14:00:00', 'Eduvos Pretoria Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'A seminar on entrepreneurship and business growth strategies.'),
                                                                        ('Eduvos Innovation Challenge', '2024-09-20', '12:00:00', 'Eduvos Cape Town Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Pitch your innovative ideas in our yearly Innovation Challenge.'),
                                                                        ('Eduvos Mathematics Workshop', '2024-08-28', '09:00:00', 'Eduvos Bloemfontein Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Advanced mathematics workshop for Eduvos students.'),
                                                                        ('Eduvos Design Expo', '2024-09-05', '10:00:00', 'Eduvos Durban Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Showcase your design projects in our annual expo.'),
                                                                        ('Eduvos Tutorial Marathon', '2024-09-25', '16:00:00', 'Eduvos Johannesburg Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'A marathon of tutorials on various subjects.'),

-- Current Events
                                                                        ('Eduvos Coding Bootcamp', '2024-08-23', '11:00:00', 'Eduvos Sandton Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Intensive coding bootcamp for beginners.'),
                                                                        ('Eduvos Leadership Conference', '2024-08-24', '09:00:00', 'Eduvos Pretoria Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Conference on leadership in tech and business.'),
                                                                        ('Eduvos UI/UX Design Workshop', '2024-08-22', '13:00:00', 'Eduvos Cape Town Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Hands-on workshop on UI/UX design trends.'),
                                                                        ('Eduvos Business Pitch Competition', '2024-08-21', '14:00:00', 'Eduvos Bloemfontein Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Pitch your startup idea to a panel of investors.'),

-- Past Events
                                                                        ('Eduvos Coding Masterclass', '2024-07-12', '11:00:00', 'Eduvos Sandton Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Coding Masterclass covering various programming languages.'),
                                                                        ('Eduvos Business Pitch', '2024-07-05', '15:00:00', 'Eduvos Pretoria Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Pitch your business ideas to investors.'),
                                                                        ('Eduvos Data Science Workshop', '2024-06-18', '14:00:00', 'Eduvos Cape Town Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Hands-on data science workshop.'),
                                                                        ('Eduvos Mathematics Exam Prep', '2024-06-02', '08:00:00', 'Eduvos Bloemfontein Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Exam prep session for all math-related subjects.'),
                                                                        ('Eduvos Innovation Talk', '2024-05-15', '10:00:00', 'Eduvos Durban Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Talk on recent innovations in technology.'),
                                                                        ('Eduvos Business Networking Event', '2024-05-08', '18:00:00', 'Eduvos Johannesburg Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Network with entrepreneurs and business leaders.'),
                                                                        ('Eduvos Tutorial Hackathon', '2024-04-25', '09:00:00', 'Eduvos Sandton Campus', 'https://www.eduvos.com/campuses/pretoria-listing.jpg', 'Hackathon focusing on tutorial-based learning.');

-- Insert Posts (30+ posts with popular tags and realistic content)
INSERT INTO Posts (title, tags, content, image, user_id) VALUES
                                                             ('Coding for Beginners: A Complete Guide', 'coding,tutorials', '<p>Learn the basics of coding with this beginner-friendly guide.</p>', 'https://codingweek.org/wp-content/uploads/2023/09/chris-ried-ieic5Tq8YMk-unsplash-scaled.jpg', 1),
                                                             ('Mathematics in Real-Life Applications', 'mathematics,innovation', '<p>Discover how mathematics is applied in various fields.</p>', 'https://essay.biz/uploads/media/2024/01/applications-of-mathematics-in-daily-life-65aa2e60b969e.jpg', 2),
                                                             ('The Role of Design in Innovation', 'design,innovation', '<p>Explore the intersection of design and innovation in today\'s world.</p>', 'https://s3.us-east-1.amazonaws.com/contents.newzenler.com/2608/library/design-inno-map618cd5c899da2_lg.png', 3),
('Top 5 Business Strategies for Startups', 'business,tutorials', '<p>Here are the top business strategies to help your startup succeed.</p>', 'https://www.ameyo.com/wp-content/uploads/2016/11/Top-5-Marketing-Strategies-for-Startups.jpg', 4),
('How to Prepare for a Coding Interview', 'coding,tutorials', '<p>Tips and tricks to ace your coding interview.</p>', 'https://media.geeksforgeeks.org/wp-content/cdn-uploads/20210531212642/Best-Tips-and-Strategies-to-Prepare-for-a-Coding-Interview.png', 5),
('Future Trends in Innovation', 'innovation,business', '<p>The future is bright with these upcoming trends in innovation.</p>', 'https://cdn.rt.emap.com/wp-content/uploads/sites/2/2017/06/11104610/Future-trends-1.jpg', 6),
('Design Tips for Your First Website', 'design,tutorials', '<p>Here are the top design tips for building your first website.</p>', 'https://mediablog.cdnpk.net/sites/10/2023/04/How-to-Create-Your-First-Design-with-Wepik.jpg', 7),
('Business and Innovation: A Winning Combination', 'business,innovation', '<p>Learn how business and innovation work together to create success.</p>', 'https://media.licdn.com/dms/image/D4D12AQHFHPpQGNypqQ/article-cover_image-shrink_600_2000/0/1703761535765?e=2147483647&v=beta&t=8DsIqKiCdIMIg58OY-_zYrvbP1jKAs9RrnnqdB2M7fs', 8),
('Introduction to Web Development', 'coding,design', '<p>An introduction to the world of web development and its possibilities.</p>', 'https://media.licdn.com/dms/image/D5612AQHAcm8mGnSxBA/article-cover_image-shrink_600_2000/0/1682869877196?e=2147483647&v=beta&t=94veL_sJbm5vJ5yVKk_4Jea4T0d0PSw62E3hb0s5PTg', 9),
('Mathematics in Cryptography', 'mathematics,innovation', '<p>Explore the role of mathematics in cryptography and data security.</p>', 'https://i.ytimg.com/vi/uNzaMrcuTM0/maxresdefault.jpg', 10),
('UI/UX Design Trends in 2024', 'design,innovation', '<p>Stay up-to-date with the latest UI/UX design trends in 2024.</p>', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQv0_d_-Ja4lVcNaO_MSxwcTN1hRTnIMKuEOQ&s', 11),
('Top 10 Programming Languages in 2024', 'coding,tutorials', '<p>Here are the top 10 programming languages you should learn in 2024.</p>', 'https://www.cleveroad.com/images/article-previews/tiobe-programming-languages-85-3x.webp', 12),
('How to Start Your Own Business', 'business,tutorials', '<p>Practical steps to start your own business and succeed.</p>', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSlsE1aa2Cx7joYJuMATNZJhgpZBVQ6ETQZ7A&s', 13),
('Innovative Startups to Watch in 2024', 'innovation,business', '<p>These startups are shaking up the world in 2024.</p>', 'https://www.startus-insights.com/wp-content/uploads/2024/04/Top-Startups-to-Watch-SharedImg-StartUs-Insights-noresize-900x506.webp', 14),
('Designing for Accessibility', 'design,tutorials', '<p>Learn how to design websites and apps that are accessible to everyone.</p>', 'https://miro.medium.com/v2/resize:fit:1200/1*OJDmcAapUKTSK5h2CboTgg.png', 15),
('Mathematical Models in AI', 'mathematics,innovation', '<p>Discover the role of mathematics in developing AI models.</p>', 'https://www.santannapisa.it/sites/default/files/2022-06/presentation.jpg', 16),
('Building a Scalable Startup', 'business,innovation', '<p>Learn how to build a startup that scales efficiently.</p>', 'https://fastercapital.com/i/Build-a-Scalable-Startup--Key-Components-of-a-Scalable-Startup.webp', 17),
('Advanced CSS Techniques', 'coding,design', '<p>Master advanced CSS techniques to take your designs to the next level.</p>', 'https://res.cloudinary.com/dz209s6jk/image/upload/v1718110916/LearningPaths/oexupgyyuusnkigeifms.jpg', 18),
('The Future of Business Automation', 'innovation,business', '<p>Business automation is the future, and here\'s why.</p>', 'https://salientprocess.com/wp-content/uploads/2024/01/01-the-future-of-hyperautomation-in-2024.jpg', 19),
                                                             ('Mastering JavaScript in 2024', 'coding,tutorials', '<p>Everything you need to know to master JavaScript in 2024.</p>', 'https://miro.medium.com/v2/resize:fit:1024/1*KXExDgjwQ-3FF7xWj5IRWw.jpeg', 20),
                                                             ('The Power of Algorithms', 'coding,mathematics', '<p>Explore how algorithms impact every aspect of technology.</p>', 'https://www.ie.edu/insights/wp-content/uploads/2020/06/El-poder-de-los-algoritmos-2000x1131-1.jpg', 11),
                                                             ('Entrepreneurship in South Africa', 'business,innovation', '<p>How to start and sustain a business in the South African market.</p>', 'https://cisp.cachefly.net/assets/articles/images/resized/0000571690_resized_enabling.jpg', 12),
                                                             ('Mastering Data Science', 'coding,mathematics', '<p>Here\'s what you need to know to master data science in 2024.</p>', 'https://media.licdn.com/dms/image/D4E12AQFm3p71Ze-yIg/article-cover_image-shrink_600_2000/0/1685596682317?e=2147483647&v=beta&t=K7rb4uJ6xgS0EMlt-FegjJEXrjjGD68KnM-MAoNX2lE', 13),
('Advanced Python Programming', 'coding,tutorials', '<p>Take your Python programming skills to the next level.</p>', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTHRW613kHW2eSY4j64DsUJ6EvneJx3DwMrfg&s', 14),
('Innovating with Blockchain', 'innovation,business', '<p>How blockchain is transforming industries worldwide.</p>', 'https://usa.visa.com/dam/VCOM/blogs/visa-explores-protocol-research-800X450-61kp.png', 15),
('The Future of E-commerce', 'business,innovation', '<p>The latest trends in e-commerce and where the industry is headed.</p>', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRmcgPq_ZbFogVJ0rtygWpCIfV0Jm8GF_cLBA&s', 16),
('UI/UX Best Practices', 'design,tutorials', '<p>Learn the best practices in UI/UX design for 2024.</p>', 'https://www.orientsoftware.com/Themes/Content/Images/blog/2022-05-17/ui-ux-best-practices.jpg', 17),
('The Art of Code Review', 'coding,tutorials', '<p>Effective code review strategies for improving code quality.</p>', 'https://kettanaito.com/blog/the-art-of-code-review.jpg', 18),
('Exploring Artificial Intelligence', 'innovation,mathematics', '<p>The latest developments in artificial intelligence.</p>', 'https://miro.medium.com/v2/resize:fit:1200/1*IknJmbo8Tn8IVHJDkN9WNQ.png', 19),
('Digital Marketing Strategies', 'business,innovation', '<p>Effective strategies for digital marketing in 2024.</p>', 'https://digitalschoolofmarketing.co.za/wp-content/uploads/2019/10/DSM_Infographics_SocialMedia5-e1572248012289.jpg', 20);




-- Insert Comments (5+ comments for each post, some with replies)
INSERT INTO Comments (post_id, user_id, content, parent_comment_id) VALUES
(1, 2, '<p>This is a great guide! Thank you for sharing!</p>', NULL),
                                                              (1, 3, '<p>Very helpful, I learned a lot!</p>', NULL),
                                                              (1, 4, '<p>Can you explain arrays in more detail?</p>', NULL),
                                                              (1, 5, '<p>Sure! Arrays are used to store multiple values in a single variable.</p>', 3),
                                                              (1, 2, '<p>That makes sense now, thanks!</p>', 5),
                                                              (2, 3, '<p>This was very informative, I enjoyed reading it.</p>', NULL),
                                                              (2, 4, '<p>Math is such a crucial skill in many fields.</p>', NULL),
                                                              (3, 5, '<p>Design and innovation are inseparable. Great post!</p>', NULL),
                                                              (4, 1, '<p>Excellent strategies for startups!</p>', NULL),
                                                              (5, 2, '<p>Thanks for the tips on coding interviews.</p>', NULL),

-- Post 11 comments
                                                              (11, 6, '<p>Algorithms are the foundation of tech!</p>', NULL),
                                                              (11, 7, '<p>I use algorithms daily in my coding practice.</p>', NULL),
                                                              (11, 8, '<p>Great post, very informative.</p>', NULL),
                                                              (11, 9, '<p>Could you provide an example of a search algorithm?</p>', NULL),
                                                              (11, 10, '<p>Check out binary search; it\'s simple but powerful.</p>', 9),

-- Post 12 comments
(12, 11, '<p>Entrepreneurship is tough but rewarding.</p>', NULL),
(12, 12, '<p>South Africa has a unique business environment.</p>', NULL),
(12, 13, '<p>This post really helps, thank you!</p>', NULL),
(12, 14, '<p>Looking to start my own business next year.</p>', NULL),
(12, 15, '<p>Good luck! It\'s a journey worth taking.</p>', 14),

-- Post 13 comments
                                                              (13, 16, '<p>Data science is such a hot field right now.</p>', NULL),
                                                              (13, 17, '<p>This article breaks down the fundamentals nicely.</p>', NULL),
                                                              (13, 18, '<p>Any tips for beginners in data science?</p>', NULL),
                                                              (13, 19, '<p>Start with Python and learn to work with data libraries.</p>', 18),
                                                              (13, 20, '<p>I\'ll definitely look into that, thanks!</p>', 19),

-- Post 14 comments
(14, 21, '<p>Python is my favorite language.</p>', NULL),
(14, 22, '<p>Great tips for advanced coders.</p>', NULL),
(14, 23, '<p>More content on Python please!</p>', NULL),
(14, 24, '<p>Can you cover some advanced libraries in the next post?</p>', NULL),
(14, 23, '<p>Sure thing, I\'ll include NumPy and Pandas.</p>', 24),

-- Post 15 comments
                                                              (15, 23, '<p>Blockchain has so much potential.</p>', NULL),
                                                              (15, 22, '<p>Industries are just starting to tap into blockchain tech.</p>', NULL),
                                                              (15, 21, '<p>Thanks for sharing this informative article.</p>', NULL),
                                                              (15, 20, '<p>What industries do you think will benefit the most?</p>', NULL),
                                                              (15, 19, '<p>Finance, healthcare, and logistics are key areas.</p>', 20),

-- Post 16 comments
                                                              (16, 1, '<p>E-commerce is evolving so fast!</p>', NULL),
                                                              (16, 2, '<p>Great insights into the future of e-commerce.</p>', NULL),
                                                              (16, 3, '<p>Looking forward to the next big thing in online shopping.</p>', NULL),
                                                              (16, 4, '<p>Thanks for breaking this down!</p>', NULL),
                                                              (16, 5, '<p>Very insightful, thanks for sharing.</p>', NULL),

-- Post 17 comments
                                                              (17, 6, '<p>UI/UX is so important for any project.</p>', NULL),
                                                              (17, 7, '<p>Thanks for sharing these best practices.</p>', NULL),
                                                              (17, 8, '<p>More posts like this please!</p>', NULL),
                                                              (17, 9, '<p>Do you have any tips for beginners in UI/UX?</p>', NULL),
                                                              (17, 10, '<p>Start with wireframing and user flow design.</p>', 9),

-- Post 18 comments
                                                              (18, 11, '<p>Code reviews help improve my coding skills.</p>', NULL),
                                                              (18, 12, '<p>This is a great guide to effective code reviews.</p>', NULL),
                                                              (18, 13, '<p>We should implement this process at work.</p>', NULL),
                                                              (18, 14, '<p>Do you have any examples of bad code reviews?</p>', NULL),
                                                              (18, 15, '<p>Yes, I\'ve seen many vague and unhelpful comments.</p>', 14),

-- Post 19 comments
(19, 16, '<p>AI is the future!</p>', NULL),
(19, 17, '<p>Great breakdown of AI concepts.</p>', NULL),
(19, 18, '<p>Looking forward to more content like this.</p>', NULL),
(19, 19, '<p>Could you do a follow-up on AI ethics?</p>', NULL),
(19, 20, '<p>That would be an interesting topic for sure.</p>', 19),

-- Post 20 comments
(20, 21, '<p>Digital marketing is essential for every business.</p>', NULL),
(20, 22, '<p>Thanks for the tips!</p>', NULL),
(20, 23, '<p>Do you have any recommendations for marketing tools?</p>', NULL),
(20, 21, '<p>Definitely check out HubSpot and Google Analytics.</p>', 23),
(20, 4, '<p>Thanks for the recommendations, will do!</p>', 24),

-- Post 21 comments

(21, 22, '<p>Algorithms are truly fascinating!</p>', NULL),
(21, 23, '<p>This post really helped me understand sorting algorithms.</p>', NULL),
(21, 1, '<p>Any plans to cover graph algorithms next?</p>', NULL),
(21, 2, '<p>Graph algorithms are on my to-do list, stay tuned!</p>', 1),
(21, 3, '<p>Looking forward to it, thanks!</p>', 2),

-- Post 22 comments
(22, 4, '<p>South Africa has so much potential for entrepreneurs!</p>', NULL),
(22, 5, '<p>This really motivated me to pursue my startup idea.</p>', NULL),
(22, 6, '<p>Any advice on securing funding locally?</p>', NULL),
(22, 7, '<p>Look into local grants and business incubators.</p>', 6),
(22, 8, '<p>I didn\'t know that! Thanks for the tip.</p>', 7),

-- Post 23 comments
                                                              (23, 9, '<p>Data science is so essential in today\'s world.</p>', NULL),
(23, 10, '<p>Great introduction to data science concepts!</p>', NULL),
(23, 11, '<p>Can you do a post on neural networks?</p>', NULL),
(23, 12, '<p>Yes, a neural networks post would be amazing!</p>', 11),
(23, 13, '<p>I\'ll cover neural networks in the next post!</p>', 12),

-- Post 24 comments
                                                              (24, 14, '<p>Advanced Python topics are always interesting.</p>', NULL),
                                                              (24, 15, '<p>Really good post, I learned a lot from this.</p>', NULL),
                                                              (24, 16, '<p>What Python libraries would you recommend for data analysis?</p>', NULL),
                                                              (24, 17, '<p>Pandas, NumPy, and Matplotlib are great for data analysis.</p>', 16),
                                                              (24, 18, '<p>Thanks, I\'ll check them out!</p>', 17),

-- Post 25 comments
(25, 19, '<p>Blockchain is the future of secure transactions.</p>', NULL),
(25, 20, '<p>This post really broke down blockchain well.</p>', NULL),
(25, 21, '<p>Do you think blockchain will replace traditional banking?</p>', NULL),
(25, 22, '<p>It has the potential, but regulations will play a big role.</p>', 21),
(25, 23, '<p>Interesting point, I hadn\'t thought of that.</p>', 22),

-- Post 26 comments
                                                              (26, 1, '<p>E-commerce trends are always evolving rapidly!</p>', NULL),
                                                              (26, 2, '<p>Great insight into the future of online retail.</p>', NULL),
                                                              (26, 3, '<p>What do you think about the impact of AI on e-commerce?</p>', NULL),
                                                              (26, 4, '<p>AI will definitely transform personalized shopping experiences.</p>', 3),
                                                              (26, 5, '<p>Excited to see how it unfolds!</p>', 4),

-- Post 27 comments
                                                              (27, 6, '<p>UI/UX is a crucial part of the user experience.</p>', NULL),
                                                              (27, 7, '<p>This post gave me new insights on designing better interfaces.</p>', NULL),
                                                              (27, 8, '<p>Any tips for improving mobile UI/UX?</p>', NULL),
                                                              (27, 9, '<p>Focus on responsive design and ease of navigation.</p>', 8),
                                                              (27, 10, '<p>Thanks, I\'ll implement these tips!</p>', 9),

-- Post 28 comments
(28, 11, '<p>Code reviews have really helped me become a better programmer.</p>', NULL),
(28, 12, '<p>This guide to code reviews is very thorough, thank you!</p>', NULL),
(28, 13, '<p>What should I focus on during a code review?</p>', NULL),
(28, 14, '<p>Focus on readability, maintainability, and logic errors.</p>', 13),
(28, 15, '<p>I\'ll make sure to do that, thanks!</p>', 14),

-- Post 29 comments
                                                              (29, 16, '<p>Artificial intelligence is rapidly advancing, so cool!</p>', NULL),
                                                              (29, 17, '<p>This post really helped me grasp AI concepts.</p>', NULL),
                                                              (29, 18, '<p>Can you cover AI ethics in your next post?</p>', NULL),
                                                              (29, 19, '<p>Yes, AI ethics is a great topic for discussion.</p>', 18),
                                                              (29, 20, '<p>Looking forward to reading it!</p>', 19),

-- Post 30 comments
                                                              (30, 21, '<p>Digital marketing strategies are a game-changer for businesses.</p>', NULL),
                                                              (30, 22, '<p>This post really helped me plan my next marketing campaign.</p>', NULL),
                                                              (30, 23, '<p>Any tool recommendations for automating digital marketing?</p>', NULL),
                                                              (30, 1, '<p>Check out HubSpot and Hootsuite for automation.</p>', 23),
                                                              (30, 2, '<p>Thanks for the recommendations!</p>', 1);





-- Insert Likes for Posts
INSERT INTO likes (post_id, user_id) VALUES
(1, 2), (1, 3), (1, 4), (2, 5), (2, 6),
(3, 7), (3, 8), (4, 9), (4, 10), (5, 11),
(11, 12), (11, 13), (12, 14), (12, 15), (13, 16),
(14, 17), (15, 18), (16, 19), (17, 20), (18, 21),
(21, 22), (21, 23), (22, 24), (22, 25), (23, 26),
(24, 27), (24, 28), (25, 29), (25, 30), (26, 31),
(26, 32), (27, 33), (27, 34), (28, 35), (28, 36),
(29, 37), (29, 38), (30, 39), (30, 40), (30, 41);

-- Insert Videos (Using YouTube video links and thumbnails)
INSERT INTO Videos (title, author_id, thumbnail, video_link) VALUES
('Introduction to Coding', 1, 'https://img.youtube.com/vi/xyz123/maxresdefault.jpg', 'https://www.youtube.com/watch?v=xyz123'),
('Mathematics for Engineers', 2, 'https://img.youtube.com/vi/abc456/maxresdefault.jpg', 'https://www.youtube.com/watch?v=abc456'),
('Innovative Design Techniques', 3, 'https://img.youtube.com/vi/def789/maxresdefault.jpg', 'https://www.youtube.com/watch?v=def789'),
('Business Growth Strategies', 4, 'https://img.youtube.com/vi/ghi012/maxresdefault.jpg', 'https://www.youtube.com/watch?v=ghi012'),
('How to Build Scalable Web Apps', 5, 'https://img.youtube.com/vi/jkl345/maxresdefault.jpg', 'https://www.youtube.com/watch?v=jkl345'),
('Understanding AI and Machine Learning', 6, 'https://img.youtube.com/vi/mno678/maxresdefault.jpg', 'https://www.youtube.com/watch?v=mno678');




