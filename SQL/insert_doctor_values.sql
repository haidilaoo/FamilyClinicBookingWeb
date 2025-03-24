-- Insert dummy data into Doctors table
INSERT INTO Doctors (first_name, last_name, specialty, contact_number, doctor_image, email, background, qualifications)
VALUES 
('John', 'Tan', 'Family Medicine', '12345678', 'doctor-john.svg', 'john.tan@example.com', 
 'Dr. John Tan is known for his warm and approachable demeanor. Patients appreciate his thorough, yet gentle approach to diagnosing and treating a range of family health issues. He believes in building strong relationships with his patients to provide personalized, long-term care.', 
 'MBBS, MMed (Family Medicine)'),

('Sarah', 'Lim', 'Pediatrics', '87654321', 'doctor-sarah.svg', 'sarah.lim@example.com', 
 'Dr. Sarah Lim is a compassionate pediatrician with a natural ability to connect with children and their families. She takes pride in making young patients feel comfortable and secure, and she works closely with parents to support their child’s health and development.', 
 'MBBS, MMed (Pediatrics)'),

('Michael', 'Lee', 'Family Medicine', '23456789', 'doctor-michael.svg', 'michael.lee@example.com', 
 'Dr. Michael Lee is a detail-oriented and patient-centered family doctor. He combines his extensive knowledge of general health issues with a calm, analytical approach, ensuring every patient’s concerns are addressed comprehensively.', 
 'MBBS, MRCGP'),

('Anna', 'Ng', 'Geriatrics', '34567891', 'doctor-anna.svg', 'anna.ng@example.com', 
 'Dr. Anna Ng has a gentle and empathetic nature, which makes her well-suited to caring for elderly patients. She is passionate about helping seniors maintain their independence and quality of life, always approaching their care with patience and respect.', 
 'MBBS, MMed (Geriatrics)'),

('James', 'Koh', 'Family Medicine', '45678912', 'doctor-james.svg', 'james.koh@example.com', 
 'Dr. James Koh is a friendly and approachable family physician who treats every patient like family. Known for his humor and positivity, he helps patients feel at ease, promoting preventive care as a key aspect of maintaining overall well-being.', 
 'MBBS, MMed (Family Medicine)'),

('Olivia', 'Teo', 'Women’s Health', '56789123', 'doctor-olivia.svg', 'olivia.teo@example.com', 
 'Dr. Olivia Teo is dedicated to women’s health, bringing an open and supportive approach to her practice. She creates a safe space for women to discuss their health concerns, providing thorough and empathetic care for every stage of life.', 
 'MBBS, MMed (Obstetrics & Gynecology)'),

('David', 'Chong', 'Family Medicine', '67891234', 'doctor-david.svg', 'david.chong@example.com', 
 'Dr. David Chong is an attentive and caring family physician who values clear communication. He is known for explaining medical information in an accessible way, empowering his patients to take an active role in their health decisions.', 
 'MBBS, MRCGP'),

('Sophia', 'Tan', 'Mental Health', '78912345', 'doctor-sophia.svg', 'sophia.tan@example.com', 
 'Dr. Sophia Tan is a warm and understanding mental health practitioner, skilled at creating a safe space for patients to discuss sensitive issues. Her empathetic approach encourages open communication, fostering a strong support system for mental wellness.', 
 'MBBS, MMed (Psychiatry)');

-- Insert dummy data into Languages table
INSERT INTO Languages (language_name)
VALUES 
('English'),
('Mandarin'),
('Malay'),
('Tamil'),
('Hokkien');

-- Associate doctors with languages in Doctor_Languages table
INSERT INTO Doctor_Languages (doctor_id, language_id)
VALUES 
(1, 1), -- Dr. John Tan speaks English
(1, 2), -- Dr. John Tan also speaks Mandarin
(2, 1), -- Dr. Sarah Lim speaks English
(2, 2), -- Dr. Sarah Lim also speaks Mandarin
(2, 3), -- Dr. Sarah Lim also speaks Malay
(3, 1), -- Dr. Michael Lee speaks English
(3, 3), -- Dr. Michael Lee speaks Malay
(4, 1), -- Dr. Anna Ng speaks English
(4, 2), -- Dr. Anna Ng also speaks Mandarin
(5, 1), -- Dr. James Koh speaks English
(5, 5), -- Dr. James Koh speaks Hokkien
(6, 1), -- Dr. Olivia Teo speaks English
(6, 2), -- Dr. Olivia Teo also speaks Mandarin
(7, 1), -- Dr. David Chong speaks English
(7, 2), -- Dr. David Chong speaks Mandarin
(7, 4), -- Dr. David Chong speaks Tamil
(8, 1), -- Dr. Sophia Tan speaks English
(8, 5); -- Dr. Sophia Tan also speaks hokkien