<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "mydb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Data array (faculty, program_name, program_code, ugpg, mod_penyampaian)
$programs = [
    ["FTMK","Diploma in Computer Science","DCS","UG","CONVENTIONAL"],
    ["FTMK","Bachelor of Computer Science (Computer Networking) with Honours","BITC","UG","CONVENTIONAL"],
    ["FTMK","Bachelor of Computer Science (Database Management) with Honours","BITD","UG","CONVENTIONAL"],    ["FTMK","Bachelor of Computer Science (Artificial Intelligence) with Honours","BITI","UG","CONVENTIONAL"],
    ["FTMK","Bachelor of Computer Science (Interactive Media) with Honours","BITM","UG","CONVENTIONAL"],
    ["FTMK","Bachelor of Computer Science (Software Development) with Honours","BITS","UG","CONVENTIONAL"],
    ["FTMK","Bachelor of Computer Science (Computer Security) with Honours","BITZ","UG","CONVENTIONAL"],
    ["FTMK","Bachelor of Information Technology (Game Technology) with Honours","BITE","UG","CONVENTIONAL"],
    ["FTMK","Master of Computer Science (Multimedia Computing)","MCSM","PG","CONVENTIONAL"],
    ["FTMK","Master of Computer Science (Database Technology)","MITD","PG","CONVENTIONAL"],
    ["FTMK","Master of Computer Science (Internetworking Technology)","MITI","PG","CONVENTIONAL"],
    ["FTMK","Master in Computer Science (Software Engineering)","MITS","PG","CONVENTIONAL"],
    ["FTMK","Master of Computer Science (Security Science)","MITZ","PG","CONVENTIONAL"],
    ["FTMK","Master in Mobile Software Development","MMSD","PG","CONVENTIONAL"],
    ["FTMK","Master of Technology (Data Science & Analytics)","MTDS","PG","CONVENTIONAL"],
    ["FTMK","Master of Technology in Data Science & Analytics","MTDL","PG","CONVENTIONAL"],
    ["FTMK","Master in Information and Communication Technology","MITA","PG","CONVENTIONAL"],
    ["FTMK","Doctor of Philosophy in Information and Communication Technology","PITA","PG","CONVENTIONAL"],
    ["FTMK","Doctor of Information Technology","PDIT","PG","CONVENTIONAL"],
    ["FTKE","Diploma in Electrical Engineering","DEL","UG","CONVENTIONAL"],
    ["FTKE","Bachelor of Electrical Engineering with Honours","BELG","UG","CONVENTIONAL"],
    ["FTKE","Bachelor of Mechatronics Engineering with Honours","BELM","UG","CONVENTIONAL"],
    ["FTKE","Bachelor of Electrical Engineering Technology (Industrial Power) with Honours","BELK","UG","CONVENTIONAL"],
    ["FTKE","Bachelor of Electrical Engineering Technology (Industrial Automation & Robotic) with Honours","BELR","UG","CONVENTIONAL"],
    ["FTKE","Bachelor of Electrical Engineering Technology with Honours","BELT","UG","CONVENTIONAL"],
    ["FTKE","Bachelor of Technology in Electrical Maintenance System with Honours","BELS","UG","CONVENTIONAL"],
    ["FTKE","Master of Science (M.Sc.) in Electrical Engineering","MEKA","PG","CONVENTIONAL"],
    ["FTKE","Master of Science (M.Sc.) in Mechatronics Engineering","MEKM","PG","CONVENTIONAL"],
    ["FTKE","Master of Electrical Engineering (Industrial Power)","MEKP","PG","CONVENTIONAL"],
    ["FTKE","Master of Electrical Engineering","MEKG","PG","CONVENTIONAL"],
    ["FTKE","Master of Mechatronics Engineering","MEKH","PG","CONVENTIONAL"],
    ["FTKE","Doctor of Philosophy (Ph. D)","PEKA","PG","CONVENTIONAL"],
    ["FTKE","Doctor of Engineering (D. Eng)","EEKA","PG","CONVENTIONAL"],
    ["FPTT","Bachelor of Technopreneurship","BTEC","UG","CONVENTIONAL"],
    ["FPTT","Bachelor of Technology Management (Technology Innovation)","BTMI","UG","CONVENTIONAL"],
    ["FPTT","Bachelor of Technology Management (High Technology Marketing)","BTMM","UG","CONVENTIONAL"],
    ["FPTT","Bachelor of Technology Management (Supply Chain Management & Logistics)","BTMS","UG","CONVENTIONAL"],
    ["FPTT","Master of Technovation","MTV","PG","CONVENTIONAL"],
    ["FPTT","Master of Business Administration (Advance Operation Management)","MBA","PG","ODL"],
    ["FPTT","Master of Business Administration (Technology and Innovation Management)","MBA","PG","ODL"],
    ["FPTT","PhD in Entrepreneurship","PIPE","PG","CONVENTIONAL"],
    ["FPTT","PhD in Technology Management","PIPM","PG","CONVENTIONAL"],
    ["FTKEK","Diploma in Electronic Engineering","DER","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Electronic Engineering With Honours","BERG","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Computer Engineering Technology (Computer Systems) With Honours","BERC","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Electronics Engineering Technology (Industrial Electronics) With Honours","BERE","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Electronics Engineering Technology (Telecommunications) With Honours","BERT","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Electronic Engineering Technology With Honours","BERZ","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Technology In Industrial Electronic Automation With Honours","BERL","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Computer Engineering with Honours","BERR","UG","CONVENTIONAL"],
    ["FTKEK","Bachelor Of Technology In Telecommunications","BERW","UG","CONVENTIONAL"],
    ["FTKEK","Master of Science in Electronic Engineering","MENA","PG","CONVENTIONAL"],
    ["FTKEK","Master of Electronic Engineering (Electronic System)","MENE","PG","CONVENTIONAL"],
    ["FTKEK","Master of Electronic Engineering (Telecommunication System)","MENT","PG","CONVENTIONAL"],
    ["FTKEK","Master of Electronic Engineering (Computer Engineering)","MENC","PG","CONVENTIONAL"],
    ["FTKEK","Doctor of Philosophy (Electronic Engineering)","PEKE","PG","CONVENTIONAL"],
    ["FTKEK","Doctor of Philosophy (Computer Engineering)","PEKC","PG","CONVENTIONAL"],
    ["FTKEK","Doctor of Philosophy (Telecommunication Systems)","PENT","PG","CONVENTIONAL"],
    ["FTKIP","Diploma of Manufacturing Engineering","DMI","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Manufacturing Engineering","BMIG","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Industrial Engineering","BMIF","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Manufacturing Engineering Technology (Product Design)","BMID","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Manufacturing Engineering Technology (Process and Technology)","BMIP","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Manufacturing Engineering Technology","BMIW","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Technology in Welding","BMIK","UG","CONVENTIONAL"],
    ["FTKIP","Bachelor of Technology in Industrial Machining","BMIM","UG","CONVENTIONAL"],
    ["FTKIP","Master of Manufacturing Engineering (Advanced Materials & Processing)","MMFB","PG","CONVENTIONAL"],
    ["FTKIP","Master of Manufacturing Engineering (Manufacturing System Engineering)","MMFS","PG","CONVENTIONAL"],
    ["FTKIP","Master of Manufacturing Engineering (Industrial Engineering)","MMFD","PG","CONVENTIONAL"],
    ["FTKIP","Master of Manufacturing Engineering (Quality System Engineering)","MMFQ","PG","CONVENTIONAL"],
    ["FTKIP","Master of Science in Manufacturing Engineering","MMFA","PG","CONVENTIONAL"],
    ["FTKIP","Doctor of Philosophy (Manufacturing Engineering)","PMFA","PG","CONVENTIONAL"],
    ["FTKIP","Doctor of Engineering (Manufacturing Engineering)","EMFA","PG","CONVENTIONAL"],
    ["FAIX","Bachelor of Computer Science (Computer Security) with Honours","BAXZ","UG","CONVENTIONAL"],
    ["FAIX","Bachelor of Computer Science (Artificial Intelligence) with Honours","BAXI","UG","CONVENTIONAL"],
    ["FAIX","Master of Computer Science (Security Science)",NULL,"PG","CONVENTIONAL"],
    ["FAIX","Master of Technology (Data Science and Analytics)","MAXL","PG","ODL"],
    ["FTKM","Diploma in Mechanical Engineering","DMC","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Automotive Engineering with Honours","BMCK","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Mechanical Engineering with Honours","BMCG","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Mechanical Engineering Technology (Automotive Technology) with Honours","BMMA","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Mechanical Engineering Technology (Refrigeration and AirConditioning Systems) with Honours","BMMH","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Mechanical Engineering Technology (Maintenance Technology) with Honours","BMMM","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Mechanical Engineering Technology with Honours","BMMV","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Manufacturing Engineering Technology (Process and Technology) with Honours","BMMP","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Manufacturing Engineering Technology (Product Design) with Honours","BMMD","UG","CONVENTIONAL"],
    ["FTKM","Bachelor of Manufacturing Engineering Technology with Honours","BMMW","UG","CONVENTIONAL"],
    ["FTKM","Master of Mechanical Engineering (Energy Engineering)","MMKE","PG","CONVENTIONAL"],
    ["FTKM","Master of Mechanical Engineering (Automotive)","MMKA","PG","CONVENTIONAL"],
    ["FTKM","Master of Mechanical Engineering (Product Design)","MMKD","PG","CONVENTIONAL"],
    ["FTKM","Master of Mechanical Engineering","MMKM","PG","CONVENTIONAL"],
    ["IPTK","Master of Engineering Business Management","MIEM","PG","CONVENTIONAL"],
    ["IPTK","Master of Business Information Management","MIIM","PG","CONVENTIONAL"],
];

// Prepared statement
$sql = "INSERT INTO programs (faculty, program_name, program_code, ugpg, target, achieve, partial_accreditation, full_accreditation, mod_penyampaian) 
        VALUES (?, ?, ?, ?, NULL, NULL, '', '', ?)";
$stmt = $conn->prepare($sql);

foreach ($programs as $p) {
    $stmt->bind_param("sssss", $p[0], $p[1], $p[2], $p[3], $p[4]);
    $stmt->execute();
}

echo "✅ Programs inserted successfully!";
$conn->close();
?>
