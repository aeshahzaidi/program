<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mydb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if not exists
$insert_sql = "
INSERT INTO program_details 
(program_id, duration, recognition, prerequisite, PLO, PEO, description, feesft_local, feesft_international, feespt_local, feespt_international) 
VALUES 
(40,
 '1600 Hours 00 Minutes',
 'Master of Business Administration',
 'This MBA program also accepts entry through the Accreditation of Prior Experiential Learning (APEL A) pathway, providing an opportunity for individuals without formal academic qualifications to pursue postgraduate studies based on their work experience and prior learning. To be eligible through APEL A, applicants must be Malaysian citizens aged 30 years and above, possess at least STPM, diploma, or equivalent qualifications, and demonstrate significant working experience relevant to the field of study. Candidates must undergo a comprehensive APEL A assessment, including a portfolio submission and an aptitude test conducted by the Malaysian Qualifications Agency (MQA). This pathway reflects our commitment to recognising professional expertise and lifelong learning, enabling more learners to access quality education and advance in their careers.',
 'PLO1: Critically evaluate and apply the principles of business administration in diverse contexts.

PLO2: Design and implement innovative solutions for complex business problems using advanced methodologies

PLO3: Exemplify practical leadership and management skills within the local and global business industry.

PLO4: Engage in advanced social discourse in diverse and complex business environments.

PLO5: Collaborate effectively and strategically with diverse teams in professional and cross-cultural settings. 

PLO6: Leverage digital skills responsibly and effectively as part of continuous professional development in business administration.

PLO7: Analyze and interpret data using advanced numeracy skills for strategic decision-making and complex problem-solving.

PLO8: Demonstrate advanced leadership capabilities and take full responsibility for managing diverse teams in challenging business environments.

PLO9: Foster and maintain personal development strategies for continuous learning and adaptability in the rapidly evolving business landscape.

PLO10: Develop and apply entrepreneurial skills to create and implement innovative solutions to strategic business challenges.

PLO11: Uphold and advocate for professional ethics, values, and attitudes in all aspects of business administration',
 'PEO1: Demonstrate mastery of theoretical and practical knowledge in business. 

PEO2: Demonstrate comprehensive managerial and entrepreneurial knowledge and skills to lead effectively and responsibly in different organisations 

PEO3: Adopt and apply a broad range of digital applications and analytical techniques competently to support business functions 

PEO4: Demonstrate teamwork, interpersonal communication, creativity and innovation skills

PEO5: Commit and seek learning for continuous development',
 'Our MBA program is now available in Open and Distance Learning (ODL) mode, specially designed for ambitious professionals who seek to elevate their careers without putting them on hold. With the flexibility to study anytime, anywhere this mode empowers working adults to gain a prestigious business education that fits seamlessly into their busy schedules. The ODL platform combines the best of academic excellence with practical relevance, offering recorded lectures, interactive modules, and live virtual sessions that bring real-world business challenges to life. Learners engage in case studies, group projects, role-playing exercises, and industry-led discussions, all from the comfort of their own space. Whether climbing the corporate ladder or shifting into a leadership role, our ODL MBA gives you the competitive edge—on your terms, at your pace, and with the full support of experienced faculty and industry experts.',
 18210, 23960, NULL, NULL
),
(77,
 '1600 Hours 00 Minutes',
 'Master of Technology in Data Science and Analytics',
 NULL,
 'PLO1: utilize data science and analytics knowledge for effective and excellent practice as a data scientist and data analyst.
PLO2: conduct systematic investigations and apply critical and creative thinking to generate innovative solutions in data science and analytics.
PLO3: apply knowledge, technology and skills of data science and analytics to discover potential yet hidden information, knowledge and insights for data-driven and well-informed decision making.
PLO4: effectively interact with diverse peers, superiors, clients and experts.
PLO5: display effective communication, both orally and in writing, for data science and analytics solutions, to a diverse audience, including peers, superiors, clients, and experts.
PLO6: practise digital skills to acquire, interpret and extend knowledge in data science and analytics.
PLO7: determine suitable numerical tools to manage and resolve data science and analytics problems.
PLO8: operate effectively in community and multidisciplinary teams either as a leader or a group member, demonstrate respect for cultural diversity and contribute to their organization and society.
PLO9: apply independent and lifelong learning skills to keep up with latest relevant knowledge and cutting edge technologies in Data Science and Analytics.
PLO10: demonstrate entrepreneurial and managerial skills.
PLO11: manage research and services by applying ethics, values, attitude, professionalism and sustainable practices in data science and analytics.',
 'PEO1: have in-depth knowledge and apply enhanced technical, digital, and numeracy skills (of data science and analytics and related disciplines) to provide innovative solutions in computing.
PEO2: demonstrate effective leadership, interpersonal, and communication skills to interact effectively with a wide variety of audiences or multi-disciplinary teams, tolerate and value different global perspectives and cultures.
PEO3: engage and advocate lifelong learning activities and have an entrepreneurial mindset.
PEO4: practise professional, ethical and societal responsibilities with integrity, and show adaptability in different roles and surroundings in contributing to the community.',
 'The Master of Technology in Data Science and Analytics with ODL mode is aimed at recent graduates and industry practitioners from various academic disciplines with strong analytic and computing skills or experience. The programme is designed to equip the students with fundamental and applied knowledge, technical skills, and current technologies in the data science and analytics area. These include the fundamental principles of data science, the capability to analyse a diversity of big data, the skills of using data science tools and applying the data analytics techniques to various domains, as well as the capability to present the analytics results to the intended audience. The materials emphasise state-of-the-practice techniques, tools, and technology, and recognised methodology through university-industry collaborations. Graduates from this programme will have career opportunities as data scientists, data analysts and many more.',
 25110, 43410, 25730, 44030
);
";


if ($conn->query($insert_sql) === TRUE) {
    echo "✅ Data inserted successfully into program_details!";
} else {
    echo "Error inserting data: " . $conn->error;
}

$conn->close();
?>
