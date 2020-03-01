BEGIN TRANSACTION;

-- creatin tables woohoo

-- Users Table
CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	username TEXT NOT NULL UNIQUE,
	password TEXT NOT NULL
);

-- Users seed data
INSERT INTO users (id, username, password) VALUES (1, 'user1', '$2y$10$wSHNvQuhY0q5TYpwPQHPCeuiboaJyl3Z33Sf96CfzSvLi..lO30hy'); -- password: macandcheese
INSERT INTO users (id, username, password) VALUES (2, 'user2', '$2y$10$E.u8htm4XRyisaS/ZROSzeDub8fe.8VxKTZsqa4z3IpwsI3gpnTze'); -- password: falafel
INSERT INTO users (id, username, password) VALUES (3, 'user3', '$2y$10$wvHV0J3X.xgIGxvU3CDC3ewkb/yIicN4Iu9COx.MT08Blvf.q/DWW'); -- password: poutine
INSERT INTO users (id, username, password) VALUES (4, 'user4', '$2y$10$jcXWHqCJOc4lLi2Xni7nsec.Dw2pvjM6Glx6p8OE7jn.Eu5m9Xp1K'); -- password: soup
INSERT INTO users (id, username, password) VALUES (5, 'user5', '$2y$10$G9ElShA6GYQ262xz/60KJeL8YPIajwV5GdaIxmWmGp.kXk3to9ybC'); -- password: ceviche
INSERT INTO users (id, username, password) VALUES (6, 'user6', '$2y$10$IDJR8N/XEga69TKqHvrnyO/iqtGJjGgkkut391kxWGYbYSNhEHEje'); -- password: JEFFKOONSJEFFKOONSJEFFKOONS
INSERT INTO users (id, username, password) VALUES (7, 'user7', '$2y$10$CrNvp8gw656wbB2kpNPzXenA61HAyuTg8qB4C2g.4pQHX6CqleAIe'); -- password: anemone
INSERT INTO users (id, username, password) VALUES (8, 'user8', '$2y$10$TA9f5uYM4/Te29MFOLt4y.A1tJUpg2RvPoSIvnI.9bZdr/G3Q0fTu'); -- password: needsleep

-- Sessions Table
CREATE TABLE sessions (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	session TEXT NOT NULL UNIQUE
);


-- IMAGES TABLE

CREATE TABLE images (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	file_name TEXT NOT NULL,
	file_ext TEXT NOT NULL,
	description TEXT
);

--images seed data
-- ALL IMAGES TAKEN BY ANNIE FU (ME)
INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (1, 1, '1.png', 'png', 'jaein chillin before holi');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (2, 2, '2.png', 'png', 'sad willow');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (3, 3, '3.png', 'png', 'chillin in trillium');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (4, 4, '4.png', 'png', 'cool tree');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (5, 4, '5.png', 'png', 'pretty tree');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (6, 1, '6.jpg', 'jpg', 'ramen heart');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (7, 2, '7.jpg', 'jpg', 'hichew addiction');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (8, 3, '8.jpg', 'jpg', 'big fire');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (9, 4, '9.jpg', 'jpg', 'stairs fire');

INSERT INTO images (id, user_id, file_name, file_ext, description) VALUES (10, 4, '10.jpg', 'jpg', 'oh no slip');


--Tags Table
CREATE TABLE tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	tag TEXT NOT NULL UNIQUE
);

--Tags table seed data
INSERT INTO tags(tag) VALUES ('Peeps');

INSERT INTO tags(tag) VALUES ('Tree');

INSERT INTO tags(tag) VALUES ('Food');

INSERT INTO tags(tag) VALUES ('Library');

INSERT INTO tags(tag) VALUES ('Break');

INSERT INTO tags(tag) VALUES ('Campus');

INSERT INTO tags(tag) VALUES ('Procrastination');

INSERT INTO tags(tag) VALUES ('Nature');

INSERT INTO tags(tag) VALUES ('North');

INSERT INTO tags(tag) VALUES ('Fire');


CREATE TABLE image_tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	tag_id INTEGER NOT NULL,
	image_id INTEGER NOT NULL
);

-- Image Tags
INSERT INTO image_tags(tag_id, image_id) VALUES (1, 1);
INSERT INTO image_tags(tag_id, image_id) VALUES (8, 1);
INSERT INTO image_tags(tag_id, image_id) VALUES (5, 1);

INSERT INTO image_tags(tag_id, image_id) VALUES (2, 2);
INSERT INTO image_tags(tag_id, image_id) VALUES (8, 2);

INSERT INTO image_tags(tag_id, image_id) VALUES (2, 3);

INSERT INTO image_tags(tag_id, image_id) VALUES (2, 4);

INSERT INTO image_tags(tag_id, image_id) VALUES (2, 5);
INSERT INTO image_tags(tag_id, image_id) VALUES (9, 5);

INSERT INTO image_tags(tag_id, image_id) VALUES (3, 6);
INSERT INTO image_tags(tag_id, image_id) VALUES (5, 6);

INSERT INTO image_tags(tag_id, image_id) VALUES (3, 7);

INSERT INTO image_tags(tag_id, image_id) VALUES (5, 8);

INSERT INTO image_tags(tag_id, image_id) VALUES (7, 9);
INSERT INTO image_tags(tag_id, image_id) VALUES (8, 9);
INSERT INTO image_tags(tag_id, image_id) VALUES (9, 9);

INSERT INTO image_tags(tag_id, image_id) VALUES (7, 10);
INSERT INTO image_tags(tag_id, image_id) VALUES (8, 10);
INSERT INTO image_tags(tag_id, image_id) VALUES (9, 10);


INSERT INTO image_tags(tag_id, image_id) VALUES (10, 8);
INSERT INTO image_tags(tag_id, image_id) VALUES (10, 9);



COMMIT;
