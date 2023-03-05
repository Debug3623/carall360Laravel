<?php

use App\Treatment;
use Illuminate\Database\Seeder;

class TreatmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aTreatments = array(
            array('name' => 'Orthopedic Surgery','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Plastic Surgeon','description' => '<h2 style="text-align: justify;"><strong>About Plastic Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A plastic surgeon is an individual who is qualified and certified to be able to practice plastic surgery and perform it on an individual. Plastic surgery is reconstructing or repairing parts of the body by transferring tissue. Plastic surgery can be confused for cosmetic surgery but cosmetic surgery concerns only enhancing people&rsquo;s appearances, like breast enhancement, skin rejuvenation, face contouring, etc, while plastic surgery is aimed at repairing any defects so that they are able to reconstruct a normal function and appearance. To be a plastic surgeon, an individual needs to complete a post-graduate residency program. They can either go through an integrated residency training combining three years of general surgery and three years of plastic surgery or, they can go through an independent five year residency program in general surgery with a three year plastic surgery residency program.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Plastic surgeons can perform procedures like breast reconstruction, burn repair surgery, lower extremity reconstruction, hand surgery, scar revision surgery, and more. In addition to that, plastic surgeons can do microsurgery where they reconstruct missing tissues by transferring a piece of tissue to the reconstruction site and reconnecting blood vessels for things like hand surgery/replantation. They may also perform pediatric plastic surgery to fix any birth defects&nbsp; the child faces, for example syndactyly (webbing of the fingers and toes). They both focus on the appearance and reconstruction of functionality of the part they&rsquo;re operating on. In addition to reconstructive surgery, they are qualified to perform cosmetic surgery too so they can do both. Though with every type of surgery, there are of course complications and common risks include nerve damage, infection, implant failure, organ damage, and scarring.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">As mentioned above, there are cosmetic surgeons and plastic surgeons. Plastic surgeons can do everything a cosmetic surgeon can do while not all cosmetic surgeons can do what plastic surgeons can do.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best acupuncturists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Dentist','description' => '<h2 style="text-align: justify;"><strong>About Dentist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A dentist is a principal care dental provider. Dentists specialize in the diagnosis, treatments, and management of your overall&nbsp;</span><span style="font-weight: 400;">oral health</span><span style="font-weight: 400;">&nbsp;care needs, including gum care, fillings, crowns,&nbsp;</span><span style="font-weight: 400;">root canals</span><span style="font-weight: 400;">, </span><span style="font-weight: 400;">veneers</span><span style="font-weight: 400;">, bridges, and preventive education.&nbsp;</span><span style="font-weight: 400;">All practicing general dentists earn a BDS degree.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Endodontist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An endodontist is a dental specialist who deals with the causes, diagnosis, prevention, and treatment of diseases and injuries of the human dental pulp or the nerve of the&nbsp;</span><span style="font-weight: 400;">tooth</span><span style="font-weight: 400;">.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They do:</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">This specialist executes simple to difficult&nbsp;</span><span style="font-weight: 400;">root canal</span><span style="font-weight: 400;">&nbsp;treatments or other types of surgical root procedures. </span><span style="font-weight: 400;">A&nbsp;</span><strong>root canal</strong><span style="font-weight: 400;">&nbsp;is a procedure used to repair and save a tooth from decaying badly or getting infected. During a&nbsp;</span><strong>root canal</strong><span style="font-weight: 400;">&nbsp;procedure, the pulp and nerve are detached and the inside of the tooth is washed and sealed. The tissue surrounding the tooth become infected and abscesses may form because of lack of treatment.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;">&nbsp;</p>
          <ul style="text-align: justify;">
          <li>
          <h3><strong>&nbsp;Oral and Maxillofacial Radiologist</strong></h3>
          </li>
          </ul>
          <p style="text-align: justify;">&nbsp;</p>
          <p style="text-align: justify;"><span style="font-weight: 400;">A radiologist is the oral&nbsp;health care&nbsp;provider who specializes in the taking and analysis of all types of&nbsp;X-ray images&nbsp;and data that are used in the diagnosis and management of diseases, disorders, and conditions of the oral and maxillofacial region (</span><span style="font-weight: 400;">The&nbsp;</span>area<span style="font-weight: 400;">&nbsp;includes part of the network of your face, head, and neck).</span></p>
          <p style="text-align: justify;">&nbsp;</p>
          <ul style="text-align: justify;">
          <li>
          <h3><strong>Oral Medicine</strong></h3>
          </li>
          </ul>
          <p style="text-align: justify;">&nbsp;</p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Oral medicine is the specialty of dentistry that provides for the care of the medically complex patient through the incorporation of medicine and oral&nbsp;health care. This includes the diagnosis and supervision of oral diseases including&nbsp;oral cancer,&nbsp;candidiasis, lichen planus and aphthous&nbsp;stomatitis.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best dentist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Gastric Sleeve Surgery','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => NULL),
            array('name' => 'Gastric Bypass','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => NULL),
            array('name' => 'Gastric Band','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => NULL),
            array('name' => 'Gastric Balloon','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => NULL),
            array('name' => 'Rhinoplasty','description' => '<h2 style="text-align: justify;"><strong>What is Rhinoplasty?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Rhinoplasty also called a nose job is a surgical procedure done to change or improve the shape of the nose.&nbsp;</span><span style="font-weight: 400;">It can be performed for medical reasons or just for cosmetic reasons. Medical reasons can be the correction of disfigurement of nose resulted from birth defects or any other trauma.&nbsp;</span><span style="font-weight: 400;">Cosmetic reasons can be the improvement of the shape and appearance of the nose.</span></p>
          <h2 style="text-align: justify;"><strong>Risks related to the procedure</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Rhinoplasty is major surgery and carries risks such as</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Extensive bleeding</span></li>
          <li><span style="font-weight: 400;">Infections</span></li>
          <li><span style="font-weight: 400;">Reaction to anesthesia</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some other risk related to rhinoplasty may include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Difficulty in breathing through the nose</span></li>
          <li><span style="font-weight: 400;">Uneven looking nose</span></li>
          <li><span style="font-weight: 400;">Persistent pain or swelling</span></li>
          <li><span style="font-weight: 400;">Permanent scarring</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Planning rhinoplasty</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The upper portion of the nose is bone and the lower portion is cartilage. Rhinoplasty can change the shape of the bone and cartilage. While planning a nose job, discuss it with your surgeon and your surgeon will consider your facial features, the shape of your nose and then customize a plan for you.</span></p>
          <h2 style="text-align: justify;"><strong>Surgery</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Rhinoplasty is performed under local anesthesia with sedation or under the general anesthesia depending upon the complexity of the surgery and it also depends upon the preference of your surgeon.</span></p>
          <ul style="text-align: justify;">
          <li><strong>Local anesthesia: </strong><span style="font-weight: 400;">This anesthesia is only applied to the specific body part. A pain-numbing medication is injected in the nasal cavity and the patient is sedated by a medication given through the intravenous line.</span></li>
          <li><strong>General anesthesia</strong><span style="font-weight: 400;">: General anesthesia affects the whole body and the patient becomes unconscious during the surgery.</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">A small external incision is made at the base of the nose between the nostrils. The surgeon then proceeds to adjust the bone and cartilage of the nose.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">After the surgery, you are monitored and you can leave on the same day if you don&rsquo;t have any medical complications.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can consult the best plastic surgeon in your area through the HospitALL app and website.</span></p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Tummy Tuck','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Breast Augmentation','description' => '<h2 style="text-align: justify;"><strong>What is Breast Augmentation?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Breast augmentation is a plastic surgery procedure done to increase breast size. It&rsquo;s usually done by women to enhance their appearance, adjust for reduction after pregnancy, correct uneven breasts or to improve their self-confidence.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There can be risks such as infection, pain, rupture, etc. After the procedure, swelling and soreness is likely for a few weeks and bruising is also a possibility. Pain medication may be prescribed and it&rsquo;ll be advised to wear a sports bra or compression badge for extra support. Getting back to doing work can take a few weeks if it is not physically demanding and it&rsquo;ll be advised not to do any strenuous activity as the breast will be quite sensitive and raising blood pressure can be dangerous. The surgery may not give the appearance desired so an additional surgery may be needed. Weight loss or weight gain might change the appearance of the breast and they will sag as age increases and implants don&rsquo;t tend to last forever, usually 10 years at max so once again another implant may be needed.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Facelift','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Hair Transplant','description' => '<p style="text-align: justify;"><strong>What is Hair Transplant?</strong></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">A hair transplant is a type of surgery that is done to add more hair to an area where there is thin hair or no hair.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">It is performed by taking hair from a thicker area of the scalp and then grafting it to the desired area which is bald or thin. Hair is usually taken from the back of the head, but sometimes it can be taken from other parts of the body too.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">50-60 % of people globally face the issue of hair loss.</span></p>
          <p style="text-align: justify;"><strong>How the transplant is performed?</strong></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">In simpler words, hair transplant means taking hair from a part where you have abundant hair growth and then transferring it to the area of the head of thinning and balding.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before the procedure, the area is sterilized and local anesthesia is applied to the area from where the hair is taken. After these two types of methods can be performed.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Follicular unit transplantation in which piece of scalp is removed from the backside of your head and then the surgeon makes small holes in your scalp where hair will e transferred. After this hair is inserted from the removed piece of scalp. This is also called as grafting.</span></li>
          <li><span style="font-weight: 400;">Follicular unit extraction in which hair is shaved off from the back of your head and individual follicles are extracted out and then small holes are made into your scalp in which follicles are inserted.&nbsp;</span></li>
          </ul>
          <p><span style="font-weight: 400;">You can book a hair transplant procedure through the HospitALL website and app.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Laser Treatments','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Anti-Wrinkle Treatment','description' => '<h2 style="text-align: justify;"><strong>What is Anti-wrinkle treatment?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL offers the best dermatologists/skin specialists for anti-wrinkling treatments deciding what treatment is most suitable according to your needs. These treatments can include botox, laser treatment, creams, medication, and plastic surgery.</span></p>
          <p style="text-align: justify;"><br /><br /><br /></p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Simple IVF','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => NULL),
            array('name' => 'Tooth Polishing & Extraction','description' => NULL,'is_active' => '0','parent_id' => '4','created_by' => NULL),
            array('name' => 'Root Canal','description' => '<h2 style="text-align: justify;"><strong>What is Root Canal?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Root canal treatment (also called endodontics) is intended to save the tooth and circumvent the extraction of the tooth. This treatment is needed when the blood or nerve supply of the tooth called pulp becomes infected. The infection of the pulp is a painful experience.</span></p>
          <h2 style="text-align: justify;"><strong>When Would I Need a Root Canal Treatment?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Root canal treatment is only required when dental </span><span style="font-weight: 400;">X-rays</span><span style="font-weight: 400;"> show that the pulp has been damaged by a bacterial infection</span><span style="font-weight: 400;">. </span><span style="font-weight: 400;">Root canal treatment is done to save teeth from the abscess. An abscess is an inflamed pus-filled area and can cause swelling of the tissues around the tooth.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms of Pulp Infection</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The symptoms of a pulp infection include:</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Pain when eating or drinking hot or cold food and drink</span></li>
          <li><span style="font-weight: 400;">Pain when biting or chewing</span></li>
          <li><span style="font-weight: 400;">Loose tooth</span></li>
          <li><span style="font-weight: 400;">Facial Swelling</span></li>
          <li><span style="font-weight: 400;">Pus coming out from the affected area</span></li>
          <li><span style="font-weight: 400;">Color of tooth getting darker</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Steps Involved</strong></h2>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">The first step in the procedure is to take an X-ray to determine if there are any signs of infection in a surrounding bone.</span></li>
          <li><span style="font-weight: 400;">Local anesthesia is used to numb the area near the tooth.</span></li>
          <li><span style="font-weight: 400;">Hole is drilled into the tooth and the pulp along with bacteria, the decayed nerve tissue and related debris is removed from the tooth.</span></li>
          <li><span style="font-weight: 400;">Water or sodium hypochlorite is used periodically to flush away the debris.</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">The costs for this treatment can vary from practice to practice so it is important to discuss charges and treatment options beforehand. Consult the best dentist in your area for the root canal procedure.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Orthodontist','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Veneers','description' => '<h2 style="text-align: justify;"><strong>About Veneers</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Dental veneers are thin custom made tooth-colored shells that are attached to cover the front of the teeth to improve their appearance.&nbsp;</span><span style="font-weight: 400;">Veneers are made from porcelain or resin composite materials and they are permanently attached to the teeth.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Veneers are used to treat a variety of concerns</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Broken teeth</span></li>
          <li><span style="font-weight: 400;">Chipped teeth</span></li>
          <li><span style="font-weight: 400;">Gaps in teeth</span></li>
          <li><span style="font-weight: 400;">Discoloration</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some people opt veneer for cosmetic purposes. Some people get only one veneer to treat for the broken or chipped tooth, veneers are also used to create a symmetrical smile.&nbsp;</span><span style="font-weight: 400;">Veneers can last long usually more than a decade depending upon the veneers you opt for.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Consult the best dentist in your area through the HospitALL app and website.</span></p>','is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Crowns','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Hip Replacement','description' => NULL,'is_active' => '0','parent_id' => '1','created_by' => NULL),
            array('name' => 'ACL (Anterior Cruciate Ligament)','description' => '<h2 style="text-align: justify;"><strong>What is Anterior Cruciate Ligament?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The tear of an ACL(Anterior Cruciate Ligament) is a serious knee injury that usually occurs in athletes who play in high demanding sports such as basketball and football. The ACL runs diagonally in the middle of the knee. It stops the tibia from sliding out in front of the femur and also provides rotational stability to the knee. Causes of it can be changing direction rapidly, stopping suddenly, slowing down while running, landing from a jump incorrectly and direct contact or collision, like from a football tackle.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">If someone tears an ACL, then they will have pain with swelling on their knee within one day. They will also lose full range of motion, feel tenderness along the joint line and have discomfort while walking.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The only way for a torn ACL to heal is to have surgery on it and this will have to be done by reconstructing the ligament. The surgeon will replace the torn ligament with a tissue graft and this graft becomes a scaffold for a new ligament to grow on. The procedure is done with an arthroscope using small incisions, which is less invasive. This means there will be less pain from the surgery, less time spent in the hospital and a quicker recovery time.&nbsp;</span><span style="font-weight: 400;">HospitALL provides the best doctors and surgeons for ACL treatment.&nbsp;&nbsp;</span></p>
          <p style="text-align: justify;"><strong><br /><br /><br /></strong></p>','is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Knee Replacement Surgery','description' => NULL,'is_active' => '0','parent_id' => '62','created_by' => NULL),
            array('name' => 'Bariatric Surgeons','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Fertility Specialist','description' => '<h2><strong>About Fertility specialist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A fertility specialist is a reproductive endocrinologist, a physician who has specialized in a subspecialty of obstetrics and gynecology called reproductive endocrinology and infertility (REI). REI is a subject of medicine that deals with hormonal functioning as it concerns to reproduction and infertility in both women and men.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Usually, fertility doctors have OB/GYN training before they undertake specialization in infertility. Some of the specialists undergo extensive training to perform delicate medical and surgical procedures.</span></p>
          <h2 style="text-align: justify;"><strong>What they can do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Fertility experts treat reproductive health issues both in men and women. Fertility specialist should be consulted in the very earlier stages of the reproductive and conceiving issues. The fertility specialists focus on the assessment and treatment of infertility.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">When patients visit a fertility specialist, they are required to take a fertility exam. A standard fertility assessment includes physical examination and medical and sexual histories of both partners.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Men are required to undergo a sperm analysis which includes the assessment of sperm count and sperm structure and its movement. For women, their ovulation is monitored. This is done through blood tests, ultrasound, or an ovulation test kit. If a woman is ovulating, doctors perform a standard test called a hysterosalpingogram. This test is a type of X-ray done for fallopian tubes and uterus.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Sometimes laparoscopy is done which allows the doctors to analyze ovaries, uterus and fallopian tubes.&nbsp;</span><span style="font-weight: 400;">After the examination, doctors decide the type of fertility treatment that is required.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Fertility specialists deal both with male and female infertility and reproductive health issues. Male fertility is as common as female infertility. One should consult a fertility specialist if they are one of these</span></p>
          <ul>
          <li><span style="font-weight: 400;">Women who went through more than one miscarriage</span></li>
          <li><span style="font-weight: 400;">Women under 35 who didn&rsquo;t conceive after 12 months of trying</span></li>
          <li><span style="font-weight: 400;">Men with poor semen analysis</span></li>
          <li><span style="font-weight: 400;">Men with erectile dysfunction</span></li>
          <li><span style="font-weight: 400;">Women over 35 who didn&rsquo;t conceive after 6 months of trying</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before getting started with fertility treatments, search the best fertility specialist for yourself. You can book an instant appointment with the best fertility specialist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><strong>&nbsp;</strong></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Acne','description' => '<h2 style="text-align: justify;"><strong>What is Acne?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Acne is a recurring and irritable skin disease that causes spots along with pimples. They especially appear on the face, chest, upper arms, back and neck. The most common cause of it is puberty but it can occur at any age. Other triggers can include emotional stress, oily cosmetics, medications that contain androgen and lithium and menstruation. It&rsquo;s not dangerous though it can leave scars on the skin.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Acne treatment varies with severity. MIld acne can be treated with common medications such as gels, soaps, pads, creams and lotions that are applied on the skin. But it is advisable to meet with a dermatologist especially to treat more severe cases of acne. Treatments from dermatologists may include a corticosteroid injection for a growing acne cyst, oral antibiotics, oral contraceptives for women and more.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best dermatologists for acne available.&nbsp;&nbsp;&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Skin Pigmentation','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Non-Surgical Fat Reduction','description' => NULL,'is_active' => '1','parent_id' => '32','created_by' => NULL),
            array('name' => 'Surgical facelift','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Surgical fat reduction','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => NULL),
            array('name' => 'Stretch Marks','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Laser hair removal','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Laser Skin Treatments','description' => NULL,'is_active' => '0','parent_id' => '3','created_by' => NULL),
            array('name' => 'Freckles removal','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Non-Surgical Facelift','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Skin Whitening','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Scar Removal','description' => NULL,'is_active' => '0','parent_id' => '3','created_by' => NULL),
            array('name' => 'Moles, Tags, Warts and Dark Circles','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'PRP (Hair Transplant Procedure)','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Diet Plan','description' => NULL,'is_active' => '1','parent_id' => '32','created_by' => NULL),
            array('name' => 'Keloids','description' => NULL,'is_active' => '0','parent_id' => '3','created_by' => NULL),
            array('name' => 'Vitiligo','description' => '<h2><strong>What is Vitiligo?</strong></h2>
          <p><span style="font-weight: 400;">Vitiligo is a disease in which discoloration of the skin occurs in patches. This can happen at any part of your body.&nbsp;</span><span style="font-weight: 400;">Our skin color is determined by the production of melanin. Vitiligo occurs when the cells which produce melanin stop functioning or die due to some cause.&nbsp;</span><span style="font-weight: 400;">Vitiligo isn&rsquo;t contagious or life-threatening, but it carries a social stigma. People with vitiligo often feel bad about their appearance due to societal standards of beauty.</span></p>
          <h2><strong>Symptoms</strong></h2>
          <p><span style="font-weight: 400;">The main symptom of vitiligo is the patchy skin discoloration. Further signs include</span></p>
          <ul>
          <li><span style="font-weight: 400;">Graying of hair on scalp, lashes, eyebrows and beard too</span></li>
          <li><span style="font-weight: 400;">Loss of color or change in color of the inner eye color (retina)</span></li>
          <li><span style="font-weight: 400;">Premature whitening and graying of hair may occur</span></li>
          <li><span style="font-weight: 400;">Graying of eyelashes, eyebrows and beard</span></li>
          </ul>
          <p><span style="font-weight: 400;">It can start at any age but usually starts before age 20.&nbsp;</span><span style="font-weight: 400;">Discolored patches may occur at different parts of the body depending upon the type of vitiligo you have</span></p>
          <ul>
          <li><span style="font-weight: 400;">The most common type of vitiligo is called Generalized vitiligo in which patches progress symmetrically and occur in many parts of the body.</span></li>
          <li><span style="font-weight: 400;">In Segmental vitiligo, the patches occur only on one side or one part of the body.</span></li>
          <li><span style="font-weight: 400;">In localized or focal vitiligo, one few areas are affected.</span></li>
          </ul>
          <h2><strong>Treatment</strong></h2>
          <p><span style="font-weight: 400;">This condition has no cure. Treatment may only help to stop or slow the process of discoloration.&nbsp;</span><span style="font-weight: 400;">Consult the doctor when your hair, eyes and skin start losing color. The right dermatologist will suggest the treatment suiting your needs.&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Orthopedics','description' => '<h2 style="text-align: justify;"><strong>About orthopedics</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Doctors who specialize in musculoskeletal conditions are </span>orthopedics. T<span style="font-weight: 400;">hey are also called as the bone doctor.</span> <span style="font-weight: 400;">Orthopedic surgeons can repair broken and deformed bones and injuries of muscles and tendons, among other things and assist in improving function and decrease or eliminate pain. They can also work in combination with other specialists such as rehabilitation, therapists, doctors and pain managing specialists to optimize treatment. This leads to improved mobility and function, reduced pain, and improved quality of life.</span></p>
          <h2 style="text-align: justify;"><strong>What they do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">If you have pain, consider seeing an orthopedic. Orthopedic doctors treat and diagnose many types of pain all over the body, comprising:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">shoulder, elbow, wrist or hand pain</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">hip pain</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">knee pain</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">ankle or foot pain</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">back or neck pain</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Whether your pain is slight or sharp, acute or chronic, an orthopedic doctor may be able to help. Some of the injuries that can be managed with physical therapy, non-surgical treatments and sometimes surgery include:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">tendon injuries</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">meniscus tear</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">fractures, such as broken hip, broken wrist, kneecap, compression fracture of the vertebrae and others</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">ankle sprain</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For issues mentioned above, seek an </span><span style="font-weight: 400;">orthopedic doctor for the treatment. You can book an instant appointment with the best Orthopedic in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Back Pain','description' => '<h2 style="text-align: justify;"><strong>What is Back Pain?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Back pain is common and can result from medical conditions, injury and activity. It can affect people of ages, but as people grow older, they are more prone to developing lower pain. Pain in the upper back may be because of disorders in the aorta, spine inflammation and tumors in the chest. Frequent causes of back pain include strained muscles, muscle spasm, damaged disks, injuries, fractures, falls or muscle tension. There are many other reasons as to why they occur such as age, fitness, genetics and in more severe cases it can lead to a crooked posture.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are many different treatments of back pain, which depend on the severity and the person themselves. Pain relief medication, physical therapy, visiting a chiropractor, doing yoga, getting treatment with acupuncture and very rarely surgery are some treatments.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best orthopedics/back doctors available.</span></p>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Neck Pain','description' => '<h2 style="text-align: justify;"><strong>What is Neck Pain?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The neck is made up of vertebrae extending from skull to upper torso. Bones, ligaments and muscle of neck support head.&nbsp;</span><span style="font-weight: 400;">Any abnormalities, malfunctioning, injury, and inflammation cause neck pain and stiffness.&nbsp;</span><span style="font-weight: 400;">Many people experience neck pain occasionally.&nbsp; In most of the cases, it is due to bad posture. Sometimes it happens due to the injury.</span></p>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Neck pain has various causes</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Muscle tension occurs due to activities like bad posture, sleeping in a bad position or getting a jerk during exercise.</span></li>
          <li><span style="font-weight: 400;">Neck is prone to severe injuries especially in falls, car accidents and sports.</span></li>
          <li><span style="font-weight: 400;">Neck injury due to sudden jerk is called whiplash</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">If neck pain continues for more than a week and is accompanied by other symptoms, one should seek medical help immediately. You can consult the best orthopedic doctor through the HospitALL app and website.&nbsp;&nbsp;</span></p>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Sciatica','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Slipped Disc','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Fibromyalgia','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Whiplash','description' => '<h2 style="text-align: justify;"><strong>What is the treatment for</strong><strong>&nbsp;</strong><strong>Whiplash Injury?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Whiplash is a relatively common neck injury that occurs due to strain on the neck. This is caused by the forceful rapid back and forth movement of head and neck which commonly results from vehicular accidents. It may also be caused due to sports injury or physical abuse.&nbsp;</span><span style="font-weight: 400;">In this soft tissue of our neck gets extended beyond their point of motion.&nbsp;</span><span style="font-weight: 400;">It is not a life-threatening injury but may cause partial disability and long term pain if not treated timely.</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms of whiplash injury appear within 24 hours and sometimes they appear after a few days and can last for weeks.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Neck pain and stiffening of pain</span></li>
          <li><span style="font-weight: 400;">Severe headaches at the base of the skull</span></li>
          <li><span style="font-weight: 400;">Dizziness</span></li>
          <li><span style="font-weight: 400;">Impaired vision</span></li>
          <li><span style="font-weight: 400;">Constant tiredness</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Chronic whiplash injury manifests itself as</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Impaired concentration and memory</span></li>
          <li><span style="font-weight: 400;">Ringing feeling in ears</span></li>
          <li><span style="font-weight: 400;">Insomnia</span></li>
          <li><span style="font-weight: 400;">Chronic pain in neck and shoulders</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Visit an orthopedic surgeon, if you are feeling these symptoms.&nbsp;</span><span style="font-weight: 400;">Consult with the best orthopedic surgeon near you immediately.&nbsp;</span></p>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Scoliosis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Spondylosis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Spondylitis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Headaches','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Kyphosis','description' => '<h2 style="text-align: justify;"><strong>What is Kyphosis?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Kyphosis is a condition in which the spine in the upper back has an excessive curvature which is also called as a hunchback in common terms.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">The upper back of our spine has a natural slight curve to absorb shock and support the weight of our head. Hunchback or kyphosis occurs when the curvature is larger than the normal arc.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">People having kyphosis have a visible hunch on their upper back. It may appear protruding and is noticeably rounded.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">This leads to excess pressure on the spine causing extreme pain. It also leads to breathing difficulties.</span></p>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">It can affect people of any age. The main cause is poor posture. The kyphosis which occurs due to poor posture is called postural kyphosis.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Other main causes are</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Aging</span></li>
          <li><span style="font-weight: 400;">Weak muscle in the upper back</span></li>
          <li><span style="font-weight: 400;">Arthritis</span></li>
          <li><span style="font-weight: 400;">Osteoporosis</span></li>
          <li><span style="font-weight: 400;">Injury to spine</span></li>
          <li><span style="font-weight: 400;">Disc slip</span></li>
          <li><span style="font-weight: 400;">Scoliosis</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Treatment depends upon the severity of the symptoms and underlying causes</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Doctor might suggest you certain medications including</span></li>
          </ul>
          <ol style="text-align: justify;">
          <li><span style="font-weight: 400;">Pain relievers</span></li>
          <li><span style="font-weight: 400;">Osteoporosis medications</span></li>
          </ol>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">You can also seek therapy to manage kyphosis. You can do stretching exercises to improve your spinal flexibility.</span></li>
          <li><span style="font-weight: 400;">Eat a rich diet in calcium and vitamin D</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Consult a doctor if kyphosis is accompanied by</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">Pain</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Breathing difficulties</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Fatigue</span></li>
          </ul>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Osteoporosis','description' => '<h2 style="text-align: justify;"><strong>What is Osteoporosis?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Osteo means bones poroisis means porous. Osteoporosis is a condition of bones that causes the bones to become porous, brittle and weak. Bones become so weak and brittle that even mild stress will cause a fracture. These fractures occur mostly in the hip, wrists, and spine.&nbsp;</span><span style="font-weight: 400;">Osteoporosis can affect all men and women, but women especially older women who are past menopause phases are more prone to osteoporosis.</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Usually, no symptoms appear in the early stage of osteoporosis. Once the bones have weakened, the following symptoms appear.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;"> &nbsp; &nbsp; &nbsp; </span><span style="font-weight: 400;">Back pain</span></li>
          <li><span style="font-weight: 400;"> &nbsp; &nbsp; &nbsp; </span><span style="font-weight: 400;">Stooping posture</span></li>
          <li><span style="font-weight: 400;"> &nbsp; &nbsp; &nbsp; </span><span style="font-weight: 400;">Bone breakage</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;">Our bones are in constant wearing and tearing phase. Old bone is broken down and new bone is made. The likeliness of developing osteoporosis depends upon the bone density you acquired in your youth. Higher the bone density, the more you have bone mass in reserve and lesser the chance of developing osteoporosis.</p>
          <h2 style="text-align: justify;"><strong>Risk Factors</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are certain risk factors for osteoporosis.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Age</span></li>
          <li><span style="font-weight: 400;">Family history</span></li>
          <li><span style="font-weight: 400;">Body frame size</span></li>
          <li><span style="font-weight: 400;">Hormones like sex hormones, thyroid, parathyroid and adrenal</span></li>
          <li><span style="font-weight: 400;">Dietary factors</span></li>
          <li><span style="font-weight: 400;">Certain medical conditions</span></li>
          <li><span style="font-weight: 400;">Sedentary lifestyle</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment and Prevention</strong></h2>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Good nutrition and regular exercise can help bones remain healthy.</span></li>
          <li><span style="font-weight: 400;">Intake of nutrients like vitamin D, calcium supplements</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can consult the best orthopedic doctor in your area for the treatment of osteoporosis through HospitALL app or website.&nbsp;</span></p>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Osteoarthritis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Arthritis','description' => '<h2 style="text-align: justify;"><strong>What is Arthritis?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Arthritis is the inflammation of joints but the term itself is used to describe about 200 conditions that affect the joins, other connective tissue, and tissues that surround the joins. Commonly those with arthritis have joint pain and stiffness and this worsens with age. The most prevalent types are Osteoarthritis where the hands shake and rheumatoid arthritis where the immune system attacks the joints. The cause of arthritis is usually the growth in age but other factors include obesity, family history, whether you&rsquo;re male or female, and previous joint injury.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are medical treatments such as painkillers, for some types of arthritis therapy is helpful and there is also surgery. Surgical procedures include joint repair, sometimes joint surfaces can be smoothed or realigned to improve function, joint replacement, where the damaged joint is removed and replaced with an artificial joint, and joint fusion, the ends of the two bones in the joint are removed and then locked together until they heal into one unit. Treatments vary from the type of arthritis and other factors.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best bone doctors.</span></p>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Cervical Stenosis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Lumber Stenosis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Thoracic Stenosis','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Rotator Cuff Injury','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Weight Gain & Fat Injection','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Fat Injection','description' => NULL,'is_active' => '1','parent_id' => '32','created_by' => NULL),
            array('name' => 'Diet Plans','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => NULL),
            array('name' => 'Physiotherapy','description' => '<h2 style="text-align: justify;"><strong>About Physiotherapy</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Physiotherapy is a science-based technique involving different treatments and preventive measures to cure any illness or injury. Physiotherapy aims to relieve pain to help a person in functioning better and to live better.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Physical therapy helps in</span></li>
          <li><span style="font-weight: 400;">Relieving pain</span></li>
          <li><span style="font-weight: 400;">Improving movement and mobility</span></li>
          <li><span style="font-weight: 400;">Preventing disability</span></li>
          <li><span style="font-weight: 400;">Managing chronic illness like diabetes, heart diseases, etc</span></li>
          <li><span style="font-weight: 400;">Recovery after giving birth</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">People of all ages can get physical therapy as it treats various health issues.</span></p>
          <h2 style="text-align: justify;"><strong>Approaches in physiotherapy</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are three main approaches in physiotherapy</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Education and advice</span></li>
          <li><span style="font-weight: 400;">Movement and exercise</span></li>
          <li><span style="font-weight: 400;">M</span><span style="font-weight: 400;">anual therapy</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Physiotherapist recommends exercise and movements to help in improving functioning and mobility of the body including</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Exercises that improve movement and strength of a specific body part.</span></li>
          <li><span style="font-weight: 400;">Activities that help in recovering from an operation or an injury</span></li>
          <li><span style="font-weight: 400;">Hydrotherapy</span></li>
          <li><span style="font-weight: 400;">Mobility aids</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">The physiotherapist may also recommend various exercises to help manage pain in the long term. You can consult the best physiotherapist</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Gender Selection','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => NULL),
            array('name' => 'ENT Specialist','description' => '<h2 style="text-align: justify;"><strong>About ENT Specialist&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">ENT specialist is also called as an otolaryngologist. ENT is an abbreviation for Ear Nose and Throat. ENT specialist deals with issues related to ear, nose, throat and related areas in head and neck.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What they do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">They treat various medical conditions&nbsp;</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Ear conditions like infection in the ear, hearing loss and imbalance</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Throat issues like tonsils, voice issues and difficulty in swallowing</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Nasal issues like allergies and sinusitis&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Infections and tumors related to head and neck&nbsp;</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Types&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">After the completion of medical school, doctors undertake specialty training to become ENT specialist. Some further undergo training in subspecialty like&nbsp;</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Allergy: These ENT specialist treat allergies with medicines or shots called as immunology</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Reconstructive surgeries: Some specialists perform cosmetic surgeries like nose jobs and face lifts.&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Laryngology: These specialists treat diseases and issues related to larynx (voice box) and vocal cords. They also treat issues like difficulty in swallowing.&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Otology and neurotology: This specialist deal with issues related to ear like infections, hearing loss, dizziness and buzzing in ears.&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Pediatric ENT: They are trained to treat kids and have tools for treatment of kids and youngsters. They also treat children with birth defects and can also figure if the child has a speech issue.&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Rhinology: These specialist deals with nasal issues like sinusitis, nose bleeding, and loss of smell.&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Sleep Specialist: Some doctors specialize in sleep issues involving breathing like sleep apnea and snoring. They also treat difficulty in breathing during sleep.&nbsp;</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For issues mentioned above, seek ENT Specialist for the treatment. You can book an instant appointment with the best ENT Specialist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'ENT Procedure','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => NULL),
            array('name' => 'Liver Transplant','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Liver surgery','description' => NULL,'is_active' => '1','parent_id' => '101','created_by' => NULL),
            array('name' => 'Urologist','description' => '<h2><strong>About Urologist&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Urology is the branch of medicine that deals with the diseases of the female and male urinary tract. The urinary tract includes kidneys, ureters, bladder and urethra. It also deals with male reproductive organs including the penis, testes, scrotum and prostate, etc.</span></p>
          <h2 style="text-align: justify;"><strong>What they do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Urologist deals with the issues related to the urinary tract of both males and female. They also diagnose and treat reproductive tract issues in men.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some urologist deals with the general disease of the urinary tract while others specialize in a particular type of urology</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Female urology focuses on issues related to female reproductive and urinary tract</span></li>
          <li><span style="font-weight: 400;">Male infertility issues</span></li>
          <li><span style="font-weight: 400;">Neurourology deals with the issues occurring due to nervous system</span></li>
          <li><span style="font-weight: 400;">Pediatric urology deals with urinary problems in children</span></li>
          <li><span style="font-weight: 400;">Urologic oncology deals with the cancers of the urinary system</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A urologist may treat men for</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Erectile dysfunction</span></li>
          <li><span style="font-weight: 400;">Prostate gland enlargement</span></li>
          <li><span style="font-weight: 400;">Prostate cancer</span></li>
          <li><span style="font-weight: 400;">Testicular cancer</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Urologist treat women for</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Bladder prolapsed, a condition in which bladder drops down in the vagina</span></li>
          <li><span style="font-weight: 400;">Bladder cancer</span></li>
          <li><span style="font-weight: 400;">Kidney stones</span></li>
          <li><span style="font-weight: 400;">UTIs</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Urologist treat children for</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Bed-wetting issues</span></li>
          <li><span style="font-weight: 400;">Undescended testicles</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before getting started with treatments related to urology issues, search the best urologist for yourself. You can book an instant appointment with the best urologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Kidney Transplant','description' => '<h2 style="text-align: justify;"><strong>What is a Kidney Transplant?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A kidney transplant is a surgical procedure to replace a healthy kidney from a donor into a person whose kidneys no longer function properly.&nbsp;</span><span style="font-weight: 400;">The donor can be either living or deceased.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Kidneys are the vital organs, bean-shaped and located on each side of the spine below the rib cage. The main function of kidneys is to remove waste, minerals, and fluids from our blood by producing urine.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">When kidneys aren&rsquo;t functioning properly, harmful waste gets accumulated in our body which further raises blood pressure and ultimately can lead to kidney failure which is also called end-stage kidney disease.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Common causes of kidney failure ae</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Diabetes</span></li>
          <li><span style="font-weight: 400;">Chronic, uncontrolled high blood pressure</span></li>
          <li><span style="font-weight: 400;">Polycystic kidney disease</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Why kidney transplant is done?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Kidney transplant is often done for end-stage kidney disease. As compared to dialysis, kidney transplant is associated with the following benefits</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">Better quality of life</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Fewer dietary restrictions</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Lower risk of death</span></li>
          </ul>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Endovascular Surgeon','description' => '<h2 style="text-align: justify;"><strong>About Endovascular Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is a medical specialty that makes use of manual machinery and instrumental techniques on a person to either find out or treat a disease or injury, to help better the body&rsquo;s function, appearance, or to repair unwanted ruptured areas. Vascular surgery is a subspecialty&nbsp; focusing on diseases related to the vascular system i.e the arteries, veins, and lymphatic circulation. Endovascular surgeons are vascular surgeons and they treat problems relating to the blood vessels, like aneurysms. Surgeons must first obtain a bachelor&rsquo;s degree from an accredited university and while there&rsquo;s not a specific major that&rsquo;s needed to become a surgeon, most people will choose to study science, others may choose to study biology, health science, chemistry, kinesiology, and physics. While in university, they need to fulfill pre-med course requirements, gain experience, and complete their MCAT to get into medical school. Once finished with med school, the student will have to complete surgical residency which will take five years. An additional year-three years may be required to learn subspecialties.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A vascular surgeon is able to perform surgery on the aorta, carotid arteries, and lower extremities. Diseases they can treat include acute limb ischaemia, abdominal aortic aneurysm, aortic dissection, buerger&rsquo;s, chronic kidney disease, connective tissue disease, varicose veins, thoracic aortic aneurysm, portal hypertension, pseudoaneurysm, pulmonary embolism, stroke, atherosclerosis, and more. They have the skills to perform surgery techniques like endovascular surgery, angioplasty, vascular bypass, open aortic surgery, amputation, low level laser therapy, thrombectomy, open surgery, vein stripping, and more.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of Endovascular Surgeons but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best Endovascular Surgeon in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><br /><br /></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Endovascular Therapy','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Endocrinologists','description' => '<h2 style="text-align: justify;"><strong>About Endocrinologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The doctor who has the specialization in glands and the hormones they form are endocrinologists. They deal with biochemical processes that make our body functions, comprising how our body transforms food into energy and how it grows. They work with adults and kids alike.</span></p>
          <h2 style="text-align: justify;"><strong>What they can do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">They cover a lot of dimensions, diagnosing and treating conditions that affect our:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Bone metabolism, like osteoporosis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Cholesterol</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Thyroid, a butterfly-shaped gland in our neck that regulate our metabolism,&nbsp; brain growth, energy and development</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Hypothalamus, the part of our brain that regulates body temperature, thirst, hunger</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Pancreas, which formulates insulin and other substances for digestion</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Pituitary, a pea-sized gland at the base of our brain that maintains the balance of hormones</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Adrenals, glands that sit on top of our kidneys and help to regulate things like our blood pressure,&nbsp; stress response, metabolism, and sex hormones</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Reproductive glands (gonads): ovaries in women, testes in men</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Parathyroid&rsquo;s, small glands in our neck that control the calcium in our blood</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Your primary doctor can deal with diabetes, but they may refer you to an endocrinologist when:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">You\'re new to diabetes and need to learn how to handle it.</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">You take a lot of shots or use an insulin pump.</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">They don\'t have a lot of practice treating diabetes</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">When diabetes has gotten tough to manage, or your treatment isn\'t effective.</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">You have serious complications from diabetes</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of endocrinologist but they can be differentiated with the amount of experience and additional skills they have.&nbsp;</span><span style="font-weight: 400;">Endocrinologists having specialization in treating children are </span><strong>pediatric endocrinologists</strong></p>
          <h2 style="text-align: justify;"><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before getting started with treatments related to above mentioned issues, search the endocrinologist for yourself. You can book an instant appointment with the best fertility specialist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Diabetes','description' => '<h2 style="text-align: justify;"><strong>What is Diabetes?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Diabetes is known as a group of diseases that tampers with the way the body uses glucose. Glucose is integral to health as it&rsquo;s a vital way for the cells to gain the energy to make the muscles and tissues. It is also the main source of power for the brain. The reason to why diabetes happens to a person depends on the type but all diabetes leads to excess sugar in the blood and that can lead to severe health problems. Diabetes can and is usually chronic, type 1 and type 2, but it may be reversible with it being prediabetes or gestational diabetes. Prediabetes occurs when glucose levels are high, but not high enough to cause diabetes while gestational happens during pregnancy and usually leaves after pregnancy. The cause for Type 1 diabetes is unknown but it is when the body attacks insulin-producing cells leaving it with little to no insulin. Type 2 diabetes seems to be caused by genetic and environmental factors, even linked to obesity issues, though not all type 2 diabetics are overweight. With this type of diabetes, the cells become resistant to the insulin and so the pancreas cannot keep creating enough of it to overcome the resistance. If diabetes is not treated it can lead to foot damage, eye damage, skin conditions, hearing impairment, Alzheimer\'s disease, nerve damage and more.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">While it&rsquo;s not curable, there are multiple ways to treat diabetes. The main treatment is insulin therapy where insulin is periodically needed to be injected in the diabetic patient to survive and there are many different ways to do this. The method that is used depends on the patient and situation. Monitoring blood sugar is another type of treatment where it&rsquo;s checked on regularly and is made sure that it does not go too high by triggering it like drinking excessive amounts of alcohol, eating more food than needed, managing illness and stress, etc. Additional treatment includes a pancreas treatment, medication and bariatric surgery.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors for diabetes treatment.</span></p>
          <p style="text-align: justify;"><br /><br /></p>','is_active' => '1','parent_id' => '112','created_by' => NULL),
            array('name' => 'Pediatrician','description' => '<h2 style="text-align: justify;"><strong>About Pediatricians&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pediatricians are doctors who take care of the health of your child, comprising physical, behavior, and mental health issues. They\'re qualified to identify and treat childhood illnesses, from minor health problems to serious diseases. </span><span style="font-weight: 400;">&nbsp;</span><span style="font-weight: 400;">Pediatricians work in </span><span style="font-weight: 400;">hospitals</span><span style="font-weight: 400;">, particularly those working in its subfields (e.g. </span><span style="font-weight: 400;">neonatology</span><span style="font-weight: 400;">), and as outpatient </span><span style="font-weight: 400;">primary care physicians</span><span style="font-weight: 400;">.</span></p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">They keep seeing your child from birth to age two and once a year from ages two to five for "well-child visits." After age 5, your pediatrician will probably continue seeing your child every year for annual checkups. Whenever your child is sick, they\'re the first person to call. To take care of your child, your pediatrician will:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Perform physical exams</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Vaccinate your child.</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Make sure the child meet the underlying requirements of&nbsp; growth, behavior, and skills</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Identify and treat your child\'s illnesses, infections, injuries, and other health problems</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Make you aware of your child\'s health, safety, nutrition, and fitness needs</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Answer your queries about your little one\'s growth and development</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Refer you to specialists if they think your child needs expert care</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><strong>Adolescent medicine</strong><span style="font-weight: 400;"> is a medical </span><span style="font-weight: 400;">subfield</span><span style="font-weight: 400;"> that emphasizes on the care of patients who are in the </span><span style="font-weight: 400;">adolescent</span><span style="font-weight: 400;"> period of growth. The period begins at </span><span style="font-weight: 400;">puberty</span><span style="font-weight: 400;"> and lasts until growth has stopped, at which time </span><span style="font-weight: 400;">adulthood</span><span style="font-weight: 400;"> begins.&nbsp;</span></li>
          </ul>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><strong>Child abuse </strong><strong>Pediatrics </strong><span style="font-weight: 400;">deals with children who have experienced</span> <span style="font-weight: 400;">physical</span><span style="font-weight: 400;">, </span><span style="font-weight: 400;">sexual</span><span style="font-weight: 400;">, and/or </span><span style="font-weight: 400;">psychological</span><span style="font-weight: 400;"> ill-treatment or </span><span style="font-weight: 400;">inattention</span><span style="font-weight: 400;"> of a child or children, especially by a parent or a caregiver.</span></li>
          </ul>
          <h2 style="text-align: justify;"><span style="font-weight: 400;">&nbsp;</span><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Your child&rsquo;s health and well-being should be your first priority. Seek best </span><span style="font-weight: 400;">Pediatrician for your child. </span><span style="font-weight: 400;">&nbsp;You can book an instant appointment with the best </span><span style="font-weight: 400;">Pediatricians</span><span style="font-weight: 400;"> in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Congenital diseases','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => NULL),
            array('name' => 'Infectious diseases','description' => NULL,'is_active' => '0','parent_id' => '114','created_by' => NULL),
            array('name' => 'Childhood cancer','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => NULL),
            array('name' => 'Cystoscopy','description' => '<h2 style="text-align: justify;"><strong>What is Cystoscopy?&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cystoscopy is a medical procedure done by doctors for them to be able to examine the lining of the bladder and the urethra, which is the tube that carries urine of the body. A hollow tube attached with a lens is put into the urethra and is slowly advanced within the bladder. There are multiple ways to perform a cystoscopy such as it being done in a testing room having anesthetic jelly to numb on the urethra or with sedation in an outpatient procedure or to have it done in the hospital during general anesthesia. The way it&rsquo;s done depends on the situation and the reasons behind the procedure include seeing as to why there are certain signs and symptoms, like blood in urine, diagnosing bladder diseases and conditions, diagnosing an enlarged prostate and treating bladder diseases and conditions like having small tumors removed if needed.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Right after cystoscopy, the patient is allowed to resume their daily routine though if they did have sedation or general anesthesia, they may be asked to stay in the recovery area being able to leave once the effects wear off. Side effects such as bleeding from the urethra, a burning sensation when urinating and more frequent urination for the next day or so are all possibilities. To relieve some discomfort, it&rsquo;s advised to place a warm cloth on the urethra&rsquo;s opening, to drink water and to take a warm bath if allowed to do so. The results of cystoscopy may be discussed right after or at a follow-up appointment.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors for a cystoscopy procedure and urethra related problems.</span></p>
          <p style="text-align: justify;"><br /><br /></p>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Ureteroscopy','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Orthopedics','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Knee Replacement','description' => NULL,'is_active' => '1','parent_id' => '120','created_by' => NULL),
            array('name' => 'Spine Surgery','description' => NULL,'is_active' => '0','parent_id' => '62','created_by' => NULL),
            array('name' => 'Dermatologist','description' => '<h2 style="text-align: justify;"><strong>About Dermatologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A dermatologist is a doctor specialized in treating conditions related to skin, hair, nails and mucous membrane. Dermatologist deals with all the diseases and disorders related to the above mentioned areas.&nbsp;</span><span style="font-weight: 400;">They can also treat cosmetic issues. They can also help in revitalizing the appearance of skin, nails and hair.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">A dermatologist is a medical practitioner with a full license. Some beauty practitioners call themselves as dermatologist, but they don&rsquo;t have official accreditation.</span></p>
          <h2 style="text-align: justify;"><strong>What they do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Skin is the largest organ of our body and also an indicator of overall health. Dermatologist deals with all the diseases and disorders related to skin. The most common conditions they treat are acne, dermatitis, eczema, fungal infections, hair loss, warts, nail problems and psoriasis, etc.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">They may use a range of medical and cosmetic procedures to treat issues related to skin, hair, nails and hair.&nbsp;</span><span style="font-weight: 400;">They may use medication and noninvasive procedures to treat issues and sometimes invasive procedures are employed.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some approaches used are</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Chemical peels</span></li>
          <li><span style="font-weight: 400;">Cosmetic injections</span></li>
          <li><span style="font-weight: 400;">Cryotherapy</span></li>
          <li><span style="font-weight: 400;">Dermabrasion</span></li>
          <li><span style="font-weight: 400;">Laser</span></li>
          <li><span style="font-weight: 400;">Skin grafts and flaps</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Your skin is a very sensitive part of your body, seek the best dermatologist for issues related to skin, hair and nails. </span><span style="font-weight: 400;">You can book an instant appointment with the best fertility specialist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Skin cancer','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Skin Infections','description' => '<h2 style="text-align: justify;"><strong>What is Skin Cancer?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An unusual amount of growth of skin cells is skin cancer and this generally seems to happen in areas of the skin that is exposed to sunlight though, this isn&rsquo;t always the case. The disease more specifically happens because of mutations appearing within the DNA of a person&rsquo;s skin cells. These errors are the catalyst for the cells to grow at an uncontrollable rate forming a large number of cancer cells. The thin layer that is a protective cover for the skin is the epidermis and it contains three main types of cells. Squamous cells are the skin&rsquo;s inner lining, basal cells make new skin cells, and melanocytes produce melanin which gives the skin its normal color. The type of skin cancer is determined by where it originates. Squamous cell carcinoma happens on sun-exposed areas of the skin like a person&rsquo;s face, hands, and ears and those with darker skin tend to have it more on non sun exposed areas of the skin. Melanoma is cancer that can appear anywhere on the skin or cause an existing mole to become cancerous; men usually have it on their faces while women tend to have it on their lower legs. Basal cell carcinoma most of the time appears onto the neck or face where there is more exposure to the sun. Skin cancer is also caused by ultraviolet light, being exposed to toxic substances, or having a condition that weakens the immune system.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">If there is early detection of skin cancer and it is small, it can be removed quite simply by a biopsy procedure. If skin cancer is worse, then treatment such as chemotherapy, radiation therapy, Mohs surgery, freezing, and more, may be used. The type of treatment used will depend on the type of skin cancer and overall the situation of the patient.&nbsp;</span><span style="font-weight: 400;">HospitALL provides the best doctors to treat skin cancer.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Eczemas','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Nephrologist','description' => '<h2 style="text-align: justify;"><strong>About Nephrologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The doctor that specializes in the kidneys and any diseases that affect them are nephrologists.</span></p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some of the diseases nephrologists treat are:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><strong>Kidney disease</strong><span style="font-weight: 400;">:&nbsp; When your kidneys are dysfunctional over time. You possibly not have severe conditions until the disease is more complex. But if your doctor diagnoses it early, medications and lifestyle changes may help you avoid more damage.</span></li>
          <li style="font-weight: 400;"><strong>Kidney failure:</strong><span style="font-weight: 400;"> This is the late phase of kidney disease. If your nephrologist suggests dialysis (which purifies your blood with a machine), they&rsquo;ll be in charge of your care. They may also recommend a kidney transplant. This is in general managed by a different team and a nephrologist who specializes in transplants. You have to keep seeing this person until your transplant and after.</span></li>
          <li style="font-weight: 400;"><strong>Nephrotic syndrome</strong><span style="font-weight: 400;">: This is a condition in which protein leaks into your urine. It leads to swelling of different body parts.</span></li>
          <li style="font-weight: 400;"><strong>High blood pressure: </strong><span style="font-weight: 400;">When your high blood pressure is hard to control, your nephrologist can help you.</span></li>
          <li style="font-weight: 400;"><strong>Polycystic kidney disease</strong><span style="font-weight: 400;">: A condition in which fluid-filled cysts grow in your kidneys. One they got bigger, they can cause damage and may lead to kidney failure. The condition may show symptoms like back or side pain, a bigger belly, and bloody pee.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>&nbsp;Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before getting started with kidney and related treatments, search the best nephrologist for yourself. You can book an instant appointment with the best nephrologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><strong>&nbsp;</strong></p>
          <p style="text-align: justify;"><br /><br /></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Kidney cancer','description' => NULL,'is_active' => '1','parent_id' => '127','created_by' => NULL),
            array('name' => 'Hypertension','description' => NULL,'is_active' => '1','parent_id' => '127','created_by' => NULL),
            array('name' => 'Cardiologist','description' => '<h2 style="text-align: justify;"><strong>About Cardiologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A cardiologist is a specialist doctor that deals with the issues and conditions related to heart and blood vessels. A cardiologist specializes in the treatment and as well as prevention of heart diseases like heart attack, heart failure, rhythmic disturbances.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Doctors undergo extensive training after completing their MBBS to become a cardiologist.</span></p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">When a general medical doctor notices unusual symptoms like shortness of breath and constant chest pains, the patient is referred to the cardiologist.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">When a patient consults cardiologists, they examine the blood pressure, heart rate and review the medical history. Some conditions are identified by the symptoms and sometimes the patient has to take additional tests such as ECG, X-ray or blood tests. Some conditions may require specialized testing including</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Echocardiogram is a sound wave picture to view the structure of the heart and to observe the function of the heart.</span></li>
          <li><span style="font-weight: 400;">ECG&nbsp; is a recording done to record the activity to observe the abnormal heart rhythms</span></li>
          <li><span style="font-weight: 400;">Cardiac Catheterization is a test in which a small tube is inserted inside to capture pictures of heart to view the functioning of the heart.</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cardiologists can perform procedures like heart surgery, catheterization and angioplasty. They also help people in rehabilitation and treat people with heart diseases to live a normal life.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Not all the cardiologists perform surgery and tests like catheterizations. Cardiac surgeons are specialized and trained for this. Others specialize in the diagnosis and interpretation of echocardiograms and ECGs. Some specialize in the management of cholesterol and cardiac rehabilitation.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For issues mentioned above, seek Cardiologists for the treatment. You can book an instant appointment with the best Cardiologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Coronary Artery Disease','description' => '<h2 style="text-align: justify;"><strong>What is Coronary Artery Disease?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The damaging of the major blood vessels that supply the heart, oxygen, and nutrients, also known as coronary arteries, is coronary artery disease. Plaque is cholesterol-containing deposits. When plaque builds up, it causes the coronary arteries to narrow, reducing blood flow to the heart. This may lead to chest pain, shortness of breath or other symptoms. Complete blockage might result in a heart attack. As this disease develops over a period of decades, noticing a problem often only happens when there is a major blockage. The main causes of the disease are smoking, high cholesterol, diabetes, and high blood pressure. Other risks are obesity/being overweight, unhealthy diet and high amounts of stress.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The usual treatment for this disease is to go through lifestyle changes such as quitting smoking, eating healthier foods, losing any excess weight, exercising regularly and reducing stress. Medicine may also be prescribed like aspirin, which can reduce the possibility of blood clot. But this will actually depend on what is causing the disease and so medication will be given accordingly. If it becomes too severe, coronary artery bypass surgery is an option.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best heart doctors available.&nbsp;</span></p>
          <p style="text-align: justify;"><br /><br /></p>','is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Heart Failure','description' => '<h2><strong>What is Heart failure?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Heart failure is when the heart works less efficiently than normal, not being able to meet the supply of the body\'s needs for blood and oxygen; the blood moves at a slower rate through the heart and body. To make up for it, it tries to contract more strongly causing it to enlarge and because of this, the muscle mass increases. The heart may also pump faster than before. These only solve the problems temporarily though and heart failure will continue to worsen to the point they stop working. It&rsquo;s because of this some people don&rsquo;t recognize their heart problems until years after when the heart does decline. Causes of heart failure may be coronary artery disease, a heart attack, and any other conditions that overwork the heart like a high BP, diabetes, and more. It starts with the left side of the heart to eventually the right side.</span></p>
          <h2><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Treatment for heart failure depends on what stage the patient is at with Stage A being at high risk of it to Stage C being in the advanced stage of heart failure. As the patient&rsquo;s condition goes up in stages, they have to have more advanced medication and care. In the first stage (A), treatment includes quitting smoking, treat high BP, not take drugs, and having beta-blockers if the patient has had a heart attack before. Stage B through to Stage D has treatments from all the stages before and adds additional medication and treatment including surgery to repair the valve and for coronary artery disease.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best heart specialists.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Valvular Heart Disease','description' => '<h2 style="text-align: justify;"><strong>What is Valvular Heart Disease?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The human heart consists of four chambers. The upper two chambers are called as left and right atrium and the lower two chambers are called right and left ventricles.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are four valves present at the exit of each chamber and they maintain the blood flow through the heart to lungs and to the rest of the body.&nbsp;</span><span style="font-weight: 400;">The four valves are named as</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Tricuspid valve is present between right and left atrium and opens to pump blood in the right ventricle.</span></li>
          <li><span style="font-weight: 400;">Pulmonary valve controls the flow of blood between the right ventricle and lungs</span></li>
          <li><span style="font-weight: 400;">Mitral valve is present between the left atrium and left ventricle and opens up to pump the oxygenated blood from the left atrium to the left ventricle.</span></li>
          <li><span style="font-weight: 400;">The aortic</span><span style="font-weight: 400;">&nbsp;valve is responsible for the flow of blood from the left ventricle to the main artery of the body which is called the aorta.</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Heart valve disease also called Valvular Heart Disease occurs when one or more heart valves malfunction and don&rsquo;t open or close properly.&nbsp;</span><span style="font-weight: 400;">Heart valve disease can be mild, moderate or severe depending upon the symptoms. This leads to the enlargement of heart or heart failure.&nbsp;</span><span style="font-weight: 400;">Heart failure occurs when the heart is unable to pump enough blood to meet the body&rsquo;s oxygen requirement.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Heart valve disease can be treated in various ways, either by medication or by surgery to repair or replace the affected valve.</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Electrophysiology','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Critical Care','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Medicine','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Rheumatologist','description' => '<h2 style="text-align: justify;"><strong>About Rheumatologists</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Rheumatology is a division of medicine dedicated to the diagnosis and therapy of rheumatic diseases. Physicians who are specialized in rheumatology are called rheumatologists.</span></p>
          <h2 style="text-align: justify;"><strong>What they do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Rheumatologists deal mainly with immune-mediated disorders of the musculoskeletal system, autoimmune diseases, soft tissues, inherited connective tissue disorders and vasculitides.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Most of these diseases are now known to be disorders of the immune system. Rheumatology is considered to be the study and practice of medical immunology.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">A </span><span style="font-weight: 400;">rheumatologist treats the following diseases:</span></p>
          <p style="text-align: justify;"><strong>Degenerative arthropathies</strong></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Osteoarthritis</span></li>
          </ul>
          <p style="text-align: justify;"><strong>Inflammatory arthropathies</strong></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">&nbsp;</span><span style="font-weight: 400;">Crystal arthropathies: gout, pseudo gout</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Septic arthritis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Rheumatoid arthritis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Spondyloarthropathies</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Reactive arthritis (reactive arthropathy)</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Ankylosing spondylitis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Psoriatic arthropathy</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Enteropathic arthropathy</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Juvenile Idiopathic Arthritis (JIA)</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For the issues mentioned above, seek </span><span style="font-weight: 400;">Rheumatologist</span><span style="font-weight: 400;"> for treatment. You can book an instant appointment with the best </span><span style="font-weight: 400;">Rheumatologist</span><span style="font-weight: 400;"> in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Psychiatrist','description' => '<h2 style="text-align: justify;"><strong>About Psychiatrist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A psychiatrist is an MBBS doctor who has a specialization in the prevention, diagnosis, and treatment of mental illness.</span></p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A psychiatrist is educated to tell mental health problems from other medical diseases that could cause psychiatric symptoms. They also work to improve your physical conditions (such as problems with the heart or high blood pressure), and the effects of medicines (such as weight, blood sugar, blood pressure, sleep, and kidney or liver function).</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Psychiatrists help in the treatment of disorders like:&nbsp;</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Dementia&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Alzheimer\'s disease</span><span style="font-weight: 400;">&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">attention deficit hyperactivity disorder</span><span style="font-weight: 400;">&nbsp;(ADHD)</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">sleep medicine</span><span style="font-weight: 400;">&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">eating disorders</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">sexual disorders</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">early psychosis intervention</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">mood disorders</span><span style="font-weight: 400;"> and</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">anxiety disorders</span><span style="font-weight: 400;">&nbsp;such as&nbsp;</span><span style="font-weight: 400;">obsessive&ndash;compulsive disorder</span><span style="font-weight: 400;">&nbsp;(OCD) and&nbsp;</span><span style="font-weight: 400;">posttraumatic stress disorder</span><span style="font-weight: 400;">&nbsp;(PTSD)</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some psychiatrists are restricted to certain age groups.</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">&nbsp;</span><strong>Pediatric psychiatry</strong><span style="font-weight: 400;">&nbsp;is the area of psychiatry that works with children to address psychological problems.&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">&nbsp;Psychiatrists specializing in&nbsp;</span><span style="font-weight: 400;">geriatric psychiatry</span><span style="font-weight: 400;">&nbsp;work with the elderly population and are called </span><strong>geriatric psychiatrists or geropsychiatrists.</strong></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">&nbsp;Those who practice psychiatry in the workplace are called </span><strong>occupational psychiatrists.</strong></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">&nbsp;Psychiatrists working in the courtroom and reporting to the judge and jury, in both criminal and civil court cases, are called&nbsp;</span><strong>forensic psychiatrists</strong><strong>,</strong><span style="font-weight: 400;"> who also treat mentally disordered criminals and other patients.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For issues mentioned above, seek </span><span style="font-weight: 400;">Psychiatrists</span><span style="font-weight: 400;"> for the treatment. You can book an instant appointment with the best </span><span style="font-weight: 400;">Psychiatrists</span><span style="font-weight: 400;"> in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Gastroenterologist','description' => '<h2 style="text-align: justify;"><strong>About Gastroenterologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A doctor who has a specialization in the diagnosis and treatment of problems of gastrointestinal (GI) tract and liver is a </span><span style="font-weight: 400;">gastroenterologist.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">You might need to see a gastroenterologist for health issues like:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Esophagus, the tube that connects your mouth to your stomach</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Stomach</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Small intestine</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Colon</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Liver</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Rectum</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Pancreas</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Bile ducts</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Gallbladder</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Gastrologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A gastrologist is someone who has training in gastrology. Caring for the stomach with medicine and food both comes under it.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">This term was coined in early 1900s.&nbsp; But now the word has been replaced by gastroenterology.</span></p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There could be many reasons for visiting a gastroenterologist. This doctor performs routine colonoscopies, a test that looks at the inside of your colon. Your primary care doctor may also refer you to a gastroenterologist if you have problems with:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Severe diarrhea</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Swallowing</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Heartburn</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Food coming back up after you swallow</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">These could be small health issues or signs of a serious condition. Gastroenterologists have the tools and expertise to diagnose you correctly. A few of the diseases and conditions they control include:</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Colon polyps that may turn into cancer</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Crohn&rsquo;s disease</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Hepatitis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Ulcerative colitis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Cancer of the esophagus</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>&nbsp;Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For issues mentioned above, seek gastroenterologists for the treatment. You can book an instant appointment with the best </span><span style="font-weight: 400;">gastroenterologist </span><span style="font-weight: 400;">in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Neurologist','description' => '<h2><strong>About Neurologist&nbsp;</strong></h2>
          <p>Doctors who diagnose and treat problems of the brain and nervous system are neurologists. They don\'t perform surgery. Your primary doctor may suggest that you see one if he thinks you have an illness that needs specialist care.</p>
          <p><span style="font-weight: 400;">Some of the situations neurologist treats are:</span></p>
          <ul>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Amyotrophic lateral sclerosis&nbsp;</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Alzheimer\'s disease</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Brain and spinal cord injury or infection</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Brain tumor</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Back pain</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Headaches</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Epilepsy</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Multiple sclerosis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Peripheral neuropathy (a disease that affects your nerves)</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Pinched nerves</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Parkinson\'s disease</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Stroke</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Seizures</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Tremors (uncontrollable movements)</span></li>
          </ul>
          <h2><strong>What they do</strong></h2>
          <p><span style="font-weight: 400;">When you visit the neurologist, he\'ll check your medical history and your symptoms. You\'ll also have a physical exam that focuses on your brain and nerves. You may be required to take a neurological exam.&nbsp;</span></p>
          <p><span style="font-weight: 400;">He may check your:</span></p>
          <ul>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Coordination</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Speech</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Vision</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Strength</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Reflexes</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Sensation (ability to feel things)</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Mental status</span></li>
          </ul>
          <h2><strong>Types</strong></h2>
          <p><span style="font-weight: 400;">There aren&rsquo;t different types of Neurologist but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2><strong>Appointment booking</strong></h2>
          <p><span style="font-weight: 400;">Before getting started with neurological treatments, search the best neurologist for yourself. You can book an instant appointment with the best neurologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you. </span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Family Medicine','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Gynecologist','description' => '<h2 style="text-align: justify;"><strong>About Gynecologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A doctor who specializes in women\'s reproductive health is a gynecologist.</span></p>
          <ul style="text-align: justify;">
          <li><strong>Obstetricians</strong></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Obstetricians look after women during their </span><span style="font-weight: 400;">pregnancy</span><span style="font-weight: 400;"> and when the baby is born. They also deliver babies.</span></p>
          <ul style="text-align: justify;">
          <li><strong>Ob-gyn</strong></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">An ob-gyn is qualified to do all of these things.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">An ob-gyn deals with some of the most significant health issues in your life, comprising </span><span style="font-weight: 400;">birth control</span><span style="font-weight: 400;">, </span><span style="font-weight: 400;">childbirth</span><span style="font-weight: 400;">, and menopause. An ob-gyn can also screen for cancer, treat infections, and perform surgery for pelvic organ or urinary tract problems.</span></p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Examples of situations dealt by a gynecologist are:</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Cancer and pre-cancerous diseases of the female organs including ovaries, fallopian tubes, vagina, uterus, cervix, and vulva</span></li>
          <li><span style="font-weight: 400;">Amenorrhoea (absent menstrual periods)</span></li>
          <li><span style="font-weight: 400;">Infertility</span></li>
          <li><span style="font-weight: 400;">Menorrhagia (heavy menstrual periods); a common indication for hysterectomy</span></li>
          <li><span style="font-weight: 400;">Prolapse of pelvic organs</span></li>
          <li><span style="font-weight: 400;">UTI and Pelvic Inflammatory Disease</span></li>
          <li><span style="font-weight: 400;">Dysmenorrhoea (painful menstrual periods)</span></li>
          <li><span style="font-weight: 400;">Infections of the vagina (vaginitis), cervix and uterus (including fungal, bacterial, viral, and protozoal)</span></li>
          <li><span style="font-weight: 400;">Incontinence of urine</span></li>
          <li><span style="font-weight: 400;">Premenstrual Syndrome</span></li>
          <li><span style="font-weight: 400;">Other vaginal diseases</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before getting started with obs/gynae procedures or if you are looking forward to childbirth delivery, search the best gynecologist for yourself. You can book an instant appointment with the best gynecologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><strong>&nbsp;</strong></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'General Surgeon','description' => '<h2 style="text-align: justify;"><strong>About General Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is a medical specialty that makes use of manual machinery and instrumental techniques on a person to either find out or treat a disease or injury, to help better the body&rsquo;s function, appearance, or to repair unwanted ruptured areas. General surgeons are a part of a surgical speciality which can focus on the stomach, small intestine, liver, pancreas, appendix, and more. They can additionally treat diseases related to the skin, breast, trauma, hernias, etc. Surgeons must first obtain a bachelor&rsquo;s degree from an accredited university and while there&rsquo;s not a specific major that&rsquo;s needed to become a surgeon, most people will choose to study science, others may choose to study biology, health science, chemistry, kinesiology, and physics. While in university, they need to fulfill pre-med course requirements, gain experience, and complete their MCAT to get into medical school. Once finished with med school, the student will have to complete surgical residency which will take five years. An additional year-three years may be required to learn subspecialties.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A general surgeon is able to perform many types of surgeries such as trauma surgery or critical surgery, laparoscopic surgery, breast surgery, minor vascular operations, and more. Trauma surgery is the most commonly surgical procedure that general surgeons are called upon to perform, this is where the patient is critically ill or severely injured. A usual procedure includes cholecystectomy, the surgical removal of the gallbladder. General surgeons are the ones to perform the majority of non-cosmetic breast surgery like with the removal of one or both breasts to even to diagnose breast cancer.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of General Surgeons but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best General Physicians in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'General Surgery','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Neurosurgery','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Eye Specialist','description' => '<h2 style="text-align: justify;"><strong>About Eye Specialist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An eye specialist is an ophthalmologist. This is a doctor who is qualified and certified to treat and diagnose eye disorders/conditions. They can perform eye surgery, prescribe and fit eyeglasses and contact lenses to be able to solve vision problems. Many eye specialists are in scientific research to find the causes and cures for a variety of eye diseases and disorders to do with vision. To become an ophthalmologist, a person is required to have a degree in medicine and then another four to five years of ophthalmology residency training. Residency training problems for ophthalmology can require a one year pre residency training in pediatrics, general surgery, or internal medicine. They&rsquo;re also allowed to be able to use laser therapy, treat eye diseases, and perform surgery if needed.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Eye specialist/ophthalmologists can offer eye care services like vision service with eye exams, medical eye care for conditions such as iritis, chemical burns, surgical eye care for any trauma, cataracts, glaucoma, perform plastic surgery to raise eyelids or smooth out wrinkles.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are two different types of eye specialists: ophthalmologists and optometrists. Ophthalmologists provide medical and surgical eye care where they can provide vision services, medical eye care for conditions like chemical burns, surgery to remove cataracts for example, treat eye conditions related to other diseases, and plastic surgery to get rid of wrinkles or fix droopy eyelids. Optometrists offer services such as eye exams, vision tests, prescribing and fitting eyeglasses along with contact lenses, detecting diseases, injuries, disorders, and treating conditions like nearsightedness and farsightedness.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best eye specialists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Dentistry','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Pulmonologist','description' => '<h2 style="text-align: justify;"><strong>About Pulmonologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pulmonologist or chest specialist diagnoses and treats those conditions which affect the respiratory system. They have various expertise and deal with the following disorders</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Infectious diseases</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Structural disorders</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Inflammatory</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Neoplastic (tumor-related)</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Autoimmune disease</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Common conditions treated by pulmonologist are</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Asthma</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Bronchitis</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Chronic obstructive pulmonary disease</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Emphysema</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Interstitial lung disease</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Sleep apnea</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>What they do?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pulmonologist performs different tests and procedures to diagnose and treat respiratory issues. Some of the procedures which are commonly used are</span></p>
          <ul style="text-align: justify;">
          <li style="font-weight: 400;"><span style="font-weight: 400;">Imaging tests to examine the structures in lungs and chest. These include chest x-rays, chest CT scans and ultrasound.</span></li>
          <li style="font-weight: 400;"><span style="font-weight: 400;">Pulmonary function tests measure the lung volume, oxygen absorption and inflammation in the lungs. Pulmonary function tests include spirometry, lung volume tests, pulse oximetry and aerial blood gas test.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">For issues mentioned above, seek pulmonologist for the treatment. You can book an instant appointment with the best pulmonologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Radiologist','description' => '<h2><strong>About Radiologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A medical doctor who specializes in using medical imaging techniques like x-rays, magnetic resonance imaging (MRI), nuclear medicine, ultrasound, positron emission tomography (PET), and computed technology (CT). To be a radiologist, they&rsquo;d have to have graduated from a recognised medical school, passed their licensing exam, and finished a residency of minimum four years of unique postgraduate medical education in, not limited to, radiation safety, radiation effects on human anatomy, and appropriate performance, interpretation of quality radiologic, and medical imaging examinations. Additionally, many radiologists complete a fellowship where they get one-two years of training in a specific subspecialty of radiology such as nuclear medicine, cardiovascular radiology, or breast imaging.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">They can act as expert consultants to the physician that sends their patients for testing to the radiology department or clinic by helping the physician in choosing the right examination, finding out information from resulting medical images, and using test results to give the appropriate care. They can also treat diseases by using radiation (radiation oncology) or minimally invasive therapeutic intervention (interventional radiology).&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are three main types of radiologists, diagnostic and interventional radiologists and radiation oncologists. Diagnostic radiologists are the ones that see inside their patient&rsquo;s body to assess or diagnose the patient&rsquo;s condition using different imaging procedures to do so. They&rsquo;re the ones that act as expert consultants and after a lot of clinical work and related research, they may also specialize in subspecialties like breast imaging, cardiovascular radiology, chest radiology, emergency radiology, pediatric radiology, musculoskeletal radiology (muscles and skeleton)j, genitourinary radiology (reproductive and urinary systems), gastrointestinal radiology (stomach, intestines, and abdomen), and more.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Interventional Radiologists treat and diagnose their patients with the help of minimally invasive, image guided techniques like x-rays and MRI. Here, they make small incisions in the body and carefully control instruments to find the source of a medical problem delivering targeted treatments. Treatment can be for conditions such as stroke, cancer, heart disease, and more with the procedure offering less risk, pain and recovery time compared to surgery.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Lastly Radiation Oncologists are highly trained doctors who prescribe and are the ones that oversee every cancer patient&rsquo;s treatment. With radiation therapy they treat cancer and monitor the patient to see progress and adjust accordingly to provide the best quality care. They go through extensive training in cancer medicine, in safe use of radiation to treat disease, and training to manage any side effects caused by radiation.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best radiologists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Internal Medicine','description' => NULL,'is_active' => '1','parent_id' => '127','created_by' => NULL),
            array('name' => 'Dialysis','description' => '<h2 style="text-align: justify;"><strong>What is Dialysis?&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">When the kidney fails to filter blood by removing the waste and excess fluid within the body, it&rsquo;s kidney failure. The kidney is a vital organ and its functions are integral to the body, so to make up for a failed kidney, dialysis is used. It is a treatment that also filters and purifies the blood but by using a machine. It keeps the fluids and electrolytes in the body balanced when the kidneys cannot. There are three different types of dialysis. Hemodialysis, peritoneal dialysis (PD), and continuous renal replacement therapy (CRRT). Hemodialysis uses an artificial kidney that removes waste and extra fluid within the blood and it tends to be long term treatment. Peritoneal dialysis requires surgery to place a PD catheter into the stomach. It filters the blood inside a membrane inside the abdomen. CRRT is mostly only used on patients with acute kidney failure. A machine passes the blood through the tubing, a filter purifies the blood and the blood is returned to the body. This is performed from 12-24 hours a day, every day usually.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Hemodialysis is the most common treatment for dialysis and it is performed three times per week for a few hours each time, usually in the morning. It can be done in a dialysis center, at a hospital, or a doctor&rsquo;s office. This type of dialysis is usually for long-term treatment. PD takes a few hours and has to be repeated 4-6 times per day and depending on the type of it, the procedure may be done while awake or asleep. CRRT is only used for emergency situations. The type of dialysis used and the period of time it&rsquo;s used for depends on the situation. Hemodialysis and PD are possible to use at home though only if the doctor says so.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best dialysis treatment and the best nephrologists.</span></p>','is_active' => '1','parent_id' => '127','created_by' => NULL),
            array('name' => 'Kidney Transplant - Nephrology','description' => NULL,'is_active' => '0','parent_id' => '127','created_by' => NULL),
            array('name' => 'Kidney Stones','description' => '<h2 style="text-align: justify;"><strong>What are Kidney Stones?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Kidney stones are concrete deposits made of salts ad minerals from inside of the kidney. They are also called as renal lithiasis and nephrolithiasis.</span></p>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Kidney stones have no single definite causes, several factors increase the chances of getting kidney stones.&nbsp;</span><span style="font-weight: 400;">They are formed when the urine has more crystalline substances like calcium, oxalate and uric acid than fluid.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Calcium stones in the form of calcium oxalate</span></li>
          <li><span style="font-weight: 400;">Struvite stones which form in response to any infection</span></li>
          <li><span style="font-weight: 400;">Uric acid stones form in those people who don&rsquo;t take enough fluids</span></li>
          <li><span style="font-weight: 400;">Crystine stones are formed due to hereditary disorder.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Risks</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Several factors increase your risk for kidney stones</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Family history</span></li>
          <li><span style="font-weight: 400;">Dehydration</span></li>
          <li><span style="font-weight: 400;">Certain diet type</span></li>
          <li><span style="font-weight: 400;">Obesity</span></li>
          <li><span style="font-weight: 400;">Digestive disorders</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Usually, kidney stones don&rsquo;t cause any symptoms until they start moving within your kidney or pass into your bladder then you experience following symptoms</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Severe pain at sides and at back</span></li>
          <li><span style="font-weight: 400;">Radiating pain towards lower abdomen and groin area</span></li>
          <li><span style="font-weight: 400;">Pain while urinating</span></li>
          <li><span style="font-weight: 400;">Red or brown urine</span></li>
          <li><span style="font-weight: 400;">Persistent urge to urinate</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Consulting a doctor</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Consult the doctor when such signs and symptoms appear. Seek immediate consultation if you experience</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">Severe pain that you are unable to sit</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Severe pain accompanied by nausea and vomiting</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Blood in urine</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Difficulty in urination</span></li>
          </ul>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Prostate cancer','description' => '<h2 style="text-align: justify;"><strong>What is Prostate Cancer?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Prostate cancer is the most common cancer occurring in men. When prostate cancer occurs, it is usually confined to the prostate gland where it doesn&rsquo;t cause much serious harm. Some prostate cancer may require minimal or no treatment because of their slow growth and other types of prostate cancer spread aggressively.&nbsp;</span><span style="font-weight: 400;">Prostate cancer which is diagnosed early has a better chance of treatment.&nbsp;&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Initially, prostate cancer causes no signs and symptoms in its early stages and in when it advances and spread, the following symptoms appear</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Trouble in passing urine</span></li>
          <li><span style="font-weight: 400;">Appearance of blood in semen</span></li>
          <li><span style="font-weight: 400;">Feeling discomfort in the pelvis</span></li>
          <li><span style="font-weight: 400;">Erectile dysfunction</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The cause of prostate cancer is still not clear. Prostate cancer begins when some cells in the prostate become abnormal and mutations in abnormal cells cause them to grow and spread indefinitely. These cells form a tumor and it may spread to other parts of the body.</span></p>
          <h2 style="text-align: justify;"><strong>Risk Factors</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Factors that increase the risk of prostate cancer include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Age</span></li>
          <li><span style="font-weight: 400;">Family history</span></li>
          <li><span style="font-weight: 400;">Obesity</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>When to consult a doctor?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">If you experience the above-mentioned symptoms, consult a doctor. Your doctor might recommend you prostate screening test. You can consult the best urologist in your area through the HospitALL app and web portal.&nbsp;</span></p>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Bladder Problems','description' => '<h2 style="text-align: justify;"><strong>What is Bladder Problems?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Bladder problems are common and tend to disrupt everyday life by causing people to avoid social environments and making it harder to complete tasks at home. Usual bladder problems include Urinary Tract Infections (UTIs) which are the second most common infection in the body and can appear anywhere in the urinary system. Types of UTIs include a bladder infection, which causes a person to have a strong urge to urinate and a kidney infection. Another bladder problem is Lower Urinary Tract Symptoms. These are a group of symptoms such as having trouble urinating, leaking urine, urge to urinate and loss of bladder control. The most uncommon bladder problem is bladder cancer. Bladder problems may occur due to being overweight, diabetes, smoking, alcohol and more.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Treatment varies with what bladder problem the patient has but usually antibiotics are prescribed to get rid of any infections. Doctors may tell the patient to exercise, change diet, drink a lot of fluids or even surgery for more serious cases.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors for bladder problems.</span></p>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Kidney surgery','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Urology cancers','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Andrology','description' => '<h2 style="text-align: justify;"><strong>What is Andrology?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Andrology is a branch of medicine that is only related to men&rsquo;s health, specifically infertility of man and sexual dysfunction. These include back hair, a beer belly, unibrow, receding hairline, snoring, excessive sweating, color blindness, much more common in men, etc. A condition like beer belly may become life-threatening if it grows too much in size, but most of them are harmless and dealt with easily usually.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Unibrows, back hair and any other hair related problems can be erased with laser while too much of a beer belly can be treated as obesity and treated accordingly.&nbsp;</span><span style="font-weight: 400;">HospitALL has the best andrology specialists.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '467','created_by' => NULL),
            array('name' => 'Infertility','description' => '<h2 style="text-align: justify;"><strong>Infertility</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Infertility means that the couple is unable to conceive after having regular unprotected sexual intercourse without the use of any sort of birth control.&nbsp;</span><span style="font-weight: 400;">It happens when one partner is unable to contribute to conception, or if women can&rsquo;t carry a pregnancy to full term.</span></p>
          <h2 style="text-align: justify;"><strong>Causes in men</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Main causes of infertility in men are</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Low sperm count in which a man ejaculates a low number of sperms less than 15 million</span></li>
          <li><span style="font-weight: 400;">Low sperm mobility in which sperm is unable to reach the egg</span></li>
          <li><span style="font-weight: 400;">Abnormal sperm in which sperm has an unusual shape making it difficult to fertilize an egg</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">This results from the following reasons</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Medical conditions like testicular infection or cancer</span></li>
          <li><span style="font-weight: 400;">Ejaculation disorders</span></li>
          <li><span style="font-weight: 400;">Hormonal imbalance</span></li>
          <li><span style="font-weight: 400;">Genetic factors</span></li>
          <li><span style="font-weight: 400;">Some medications also risk fertility</span></li>
          <li><span style="font-weight: 400;">Radiation therapy also hinders sperm production</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Causes in women</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Main causes of infertility in women are</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">Medical disorders like ovulation disorders</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Polycystic ovary syndrome</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Hyperprolactinemia</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Poor egg quality</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Thyroid issues</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Problems in the fallopian tube</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Endometriosis</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">The ability of conceiving falls after age 32</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Being obese or overweight</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Sexually transmitted infections</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">A lack of nutrients and minerals like folic acid, zinc, vitamin B12</span></li>
          </ul>
          <p><span style="font-weight: 400;">You can seek treatment from infertility specialists through the HospitALL app or website.&nbsp;</span></p>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Children','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'undescended testes','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Hypospadias','description' => '<h2 style="text-align: justify;"><strong>What is hypospadias?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The opening of the urethra being on the underside of the penis instead of the tip is a birth defect called hypospadias. The appearance of the penis is different and those with it may have problems urinating because it may cause abnormal spraying. Women 35 and over are more likely to have a boy with hypospadias, exposure to certain substances while pregnant is another reason for the defect, genetics, or even family history can cause it too.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A single correction surgery, or sometimes more than one, is needed to fix the defect. Afterward, the penis looks completely normal and the boy can have normal urination and reproduction. After the surgery, there will be a couple of visits to the surgeon and regular follow-ups with the child&rsquo;s pediatric urologist is recommended to check how the healing of it and any possible complications</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors and surgeons to treat hypospadias.</span></p>','is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Hernia','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Carotid Artery disease','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Abdominal Aortic Aneurysms','description' => '<h2><strong>What is Abdominal Aortic Aneurysm </strong></h2>
          <p><span style="font-weight: 400;">An abdominal aortic aneurysm is a large area in the lower aorta. The aorta is the largest blood vessel that supplies blood to the body. If the aneurysm on the aorta ruptures/bursts, it can cause life-threatening bleeding, resulting in rapid blood loss.</span></p>
          <h2><strong>Treatment</strong></h2>
          <p><span style="font-weight: 400;">There are two types of treatment. One is monitoring the aneurysm, where the growth of it is checked on a regular basis and further growth is prevented by managing medical conditions that lead to this, such as high blood pressure.</span></p>
          <p><span style="font-weight: 400;">If the aneurysm grows to 1.9-2.2 inches, then the other treatment option is surgery is:</span></p>
          <ul>
          <li><span style="font-weight: 400;">Open abdominal surgery: this is where the damaged section of the aorta is removed and replaced with a synthetic tube (graft), which is sewn into place. Full recovery takes a month or more.</span></li>
          <li><span style="font-weight: 400;">Endovascular repair: this procedure is used more often as it doesn&rsquo;t interfere as much with the body. Doctors attach a synthetic graft to the end of a thin tube (catheter) that\'s inserted through an artery in the leg and threaded into the aorta.</span></li>
          </ul>
          <p><span style="font-weight: 400;">HospitALL provides the best doctors for the treatment of an abdominal aortic aneurysm.</span></p>
          <p><span style="font-weight: 400;">&nbsp;</span></p>
          <p><span style="font-weight: 400;">&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'peripheral Vascular Aneurysms','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Aorto-iliac Occlusive Disease','description' => '<h2 style="text-align: justify;"><strong>Aortoiliac Occlusive Disease</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Aortoiliac Occlusive Disease is when the aorta, which is the main blood vessel of the body, or iliac arteries is blocked. Sometimes those with this disease may not have any symptoms but symptoms may include pain, cramps or fatigue in the calves, buttocks or thighs when walking. As the disease gets worse, these might occur after walking quite short distances. In more severe cases, there may be pain in the feet or toes while resting, numbness and coldness in the legs and feet and possibly even gangrene or death of tissue in the feet. AOC may be caused by the hardening of the arteries and this is most prominent in smokers, those with high cholesterol, the obese, high blood pressure patients or those with genetic predisposition. Other causes of AOC include inflammation in the arteries causing blockages or radiation to the pelvis which may also cause inflammation.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The risk of the disease is usually reduced by quitting smoking, controlling cholesterol/high blood pressure, regularly exercising and managing diabetes. Medication such as aspirin, or any other medication that stops blood clotting, may also be given.&nbsp; But if surgical treatment is needed then angioplasty or a surgical will be performed.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best heart doctors in Pakistan.</span></p>','is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Peripheral Vascular Disease','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Vascular Trauma','description' => '<h2><strong>What is Vascular Trauma?</strong></h2>
          <p><span style="font-weight: 400;">Vascular trauma or vascular disease refers to the injury in a blood vessel. Injury can occur in arteries, the blood vessels that carry blood from the heart to the organs and veins which return blood to the heart.</span></p>
          <h2><strong>Causes</strong></h2>
          <p><span style="font-weight: 400;">The injuries are categorized by the type of trauma that caused them. There are usually two types of injuries.</span></p>
          <ul>
          <li><span style="font-weight: 400;">The blunt</span><span style="font-weight: 400;">&nbsp;injury occurs when a blood vessel gets stretched or crushed.</span></li>
          <li><span style="font-weight: 400;">A penetrating</span><span style="font-weight: 400;">&nbsp;injury occurs when blood vessels get punctured or torn.</span></li>
          </ul>
          <p><span style="font-weight: 400;">Either type of injuries can lead to thrombosis, a condition in which blood vessels clot and blood flow is interrupted or causes bleeding which can lead to hemorrhage which is a life-threatening condition.</span></p>
          <h2><strong>Treatment</strong></h2>
          <p><span style="font-weight: 400;">There are many treatment options and a vascular surgeon can guide you best about them.&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Diabetic Ulcers','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Buergers Disease','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Acute and chronic limb Ischemia','description' => '<h2 style="text-align: justify;"><strong>What is Acute Limb Ischaemia?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Acute Limb Ischaemia appears when blood flow suddenly becomes reduced to a limb. It can appear in all age groups but most commonly occurs in those who smoke tobacco cigarettes and have diabetes. Symptoms of it can include pain, pale appearance of the limb affected, feeling very cold, paralysis and pulselessness. Chronic Limb Ischaemia is a more severe version of ALI and brings additional symptoms such as rest pain, feeling a continuous burning pain of the lower leg or feet and tissue loss which is the development of gangrene. This can result in potential amputation of the limb.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The treatment of ALI is usually surgery with emergency embolectomy if the limb is still viable. Another option is vascular bypass where the blood flow is routed around the clot. If surgery is not possible then those with ALI may be treated with medications. Treatment also depends on many factors such as which limb is affected, how long it&rsquo;s been, individual risks, how big the lesion(s) is/are and more. For CLI, the only option is surgery similar to ALI but if there is tissue loss, then an amputation of the limb must be done.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors to treat Acute and Chronic Limb Ischaemia.</span></p>','is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Bypass and Angiolplasty','description' => NULL,'is_active' => '1','parent_id' => '107','created_by' => NULL),
            array('name' => 'Vaccinations','description' => '<h2 style="text-align: justify;"><strong>What is Vaccination?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Vaccination is one of the most effective ways of preventing diseases. The vaccine helps our immune system to recognize and fight the pathogens like bacteria and viruses and then protect us from infections and diseases they cause.&nbsp;</span><span style="font-weight: 400;">The vaccine protects from life-threatening diseases including polio, measles, influenza, typhoid and tetanus.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Vaccination is very important to mitigate the public health crisis. Vaccinations not just protect individuals but also the community through a process called herd immunity.</span></p>
          <h2 style="text-align: justify;"><strong>How does vaccination work?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Vaccination is an important part of primary prevention. They teach our immune system to recognize the pathogens and eliminate them. They prepare our body for exposure.&nbsp;</span><span style="font-weight: 400;">When vaccines are administered, the body is exposed to the safe version of disease in the form of</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Dead or inactivated pathogen</span></li>
          <li><span style="font-weight: 400;">Toxoid containing a toxin produced by a pathogen in a mild form</span></li>
          <li><span style="font-weight: 400;">Weakened pathogen</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">When our body responds to the vaccine, our adaptive immunity is built which protects us from further exposure.&nbsp;</span></p>','is_active' => '1','parent_id' => '114','created_by' => NULL),
            array('name' => 'Circumscions','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => NULL),
            array('name' => 'Growth Monitoring Growth Monitoring','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => NULL),
            array('name' => 'All childhood acute and chronic illnesses.','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => NULL),
            array('name' => 'Esophagus surgery','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Laparoscopic hepatobiliary surgery','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'advance laparoscopic surgery','description' => '<h2 style="text-align: justify;"><strong>What is Laparoscopic Surgery?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Laparoscopic surgery is a type of minimally invasive surgery that uses smaller cuts than expected. The surgeon makes a small cut and inserts a tool called a laparoscope, a slender tool that has a tiny video camera with a light attached to it on the end. With it, they can look at a video monitor and see what&rsquo;s going on in the body. If it weren&rsquo;t for this tool, the cut would need to be much larger. The surgeon will not need to reach into the body either. It was primarily used for gallbladder surgery and gynecology operations but is now also in use for the liver, intestines and more organs. There is much less scarring as compared to more invasive techniques. Advanced laparoscopic surgery can be used in some operations, this is when the surgeon puts the camera and the surgical tool together in the same opening within the skin, creating even lesser scarring, but this is more trickier as the instruments are much closer to each other. Another way can also be when the surgeon uses a device that lets them reach in with a hand but the cut made has to be bigger than when in a usual laparoscopic surgery, still being smaller than in a traditional surgery.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Procedure</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The full procedure of a laparoscopic surgery is when the surgeon makes several small cuts, usually each one is no more than a half-inch long, inserts a tube through in each opening, and then puts the camera and surgical instruments through them. The surgeon proceeds to perform the operation. After the surgery, the patient will only have small scars, they will get out og the hospital quicker, they&rsquo;ll feel less pain, heal faster, and will be able to get back to all their activities sooner.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best surgeons available with all the surgical techniques available.</span></p>','is_active' => '1','parent_id' => '32','created_by' => NULL),
            array('name' => 'Breast, thyroid and colorectal cancer operations','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Hysterectomy, Cholecystectomy, Appendectomy','description' => NULL,'is_active' => '0','parent_id' => '144','created_by' => NULL),
            array('name' => 'Diabetic Foot','description' => '<h2 style="text-align: justify;"><strong>Diabetic Foot</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Diabetes when untreated can cause serious problems like a diabetic foot. This may damage the nerves within the legs and feet and if this happens, the patient might not feel pain, cold, or heat. This condition is called diabetic neuropathy and if a cut or soreness isn&rsquo;t felt on the feet because of this, it could become worse causing infection. The foot may not even work properly with the nerves affected. There may also be poor blood flow because of diabetes so cuts take longer to heal and this may result in infection to the point that gangrene can develop, this condition is called peripheral vascular disease. Other common problems include athlete\'s foot, dry skin, blisters, fungal infections, etc&hellip;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Treatment for a diabetic foot is the same for diabetes, it must be controlled and the doctor&rsquo;s advice must be followed all the way through. Though to specifically prevent it, it&rsquo;s advised to wash the feet with warm water, apply lotion if the skin is dry, wear socks, and wear shoes that fit well. It&rsquo;s best to see the doctor for treatment as every situation is different.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best diabetic doctors available.</span></p>','is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Parotid Surgery','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Intestinal Surgery','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Haemorrhoidectomy','description' => NULL,'is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Abdominal Surgery','description' => '<h2><strong>What is Abdominal Surgery?</strong></h2>
          <p><span style="font-weight: 400;">Abdominal surgery is an operation done on organs including, the gallbladder, stomach, pancreas, appendix, small or large intestine, or appendix. Abdominal surgery may happen for many reasons such as having an infection, pain, tumors or obstruction. Common surgeries include an appendectomy, a hernia removal, and abdominal exploration and surgery for inflammatory bowel disease.</span></p>
          <h2><strong>Treatment</strong></h2>
          <p><span style="font-weight: 400;">The surgery may either be performed as an open or laparoscopic procedure:</span></p>
          <ul>
          <li><span style="font-weight: 400;">Open Procedure: the surgeon will make a traditional incision and this will be closed with sutures or staples.</span></li>
          <li><span style="font-weight: 400;">Laparoscopic Procedure: the surgeon will make multiple tiny incisions and then inserts instruments that are attached to a camera, allowing a clear view of the internal organs.</span></li>
          </ul>
          <p><span style="font-weight: 400;">HospitALL provides the best doctors for abdominal surgery.</span></p>
          <p><span style="font-weight: 400;">&nbsp;</span></p>','is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'X-Ray','description' => NULL,'is_active' => '1','parent_id' => '148','created_by' => NULL),
            array('name' => 'Dental Filling (Composite & Gic)','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Pulpotomy & Pulpectomy','description' => NULL,'is_active' => '1','parent_id' => '148','created_by' => NULL),
            array('name' => 'Scaling (Cleaning) & Teeth Polishing','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Dentures','description' => '<h2 style="text-align: justify;"><strong>What are Dentures?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Dentures are removable replacements for missing teeth and surrounding tissues. They can either be complete, where all teeth are missing and replaced or partial when there are still some natural teeth remaining. A complete denture may be done with the &lsquo;immediate&rsquo; method, where dentures are placed immediately after the removal of all teeth. Or it may be done with the &lsquo;conventional&rsquo; method, where the denture procedure is done 8-12 weeks after the removal of the teeth allowing the gum tissue to heal first. Complete dentures are more of a temporary solution until the conventional can be done because the structures of the teeth tissues change as it heals. Therefore, further adjustments will be required later. Partial dentures are used when there are one or more natural teeth remaining. The replacement teeth are attached to a pink or gum-colored plastic base, it is occasionally connected by a metal framework that holds the denture in place in the mouth.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The dentures, once inserted, can feel out of place or loose for a few weeks. But this will change when the cheek muscles and tongue adapt to keep them in place making them easy to put in and to remove. It may also feel uncomfortable to eat with dentures on and pronouncing certain things may be hard, but the patient will get used to this the more they wear them. Denture adhesive can help with better tight-fitting and dentures are safe when used as directed. Applying too much adhesive might cause harm to soft and hard tissues which can at times have inflammation appear.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best dentists available.&nbsp;</span></p>','is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Zirconium Crowns','description' => NULL,'is_active' => '1','parent_id' => '148','created_by' => NULL),
            array('name' => 'Periodontic (Gum Treatment)','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Tmj Disorders Treatment','description' => NULL,'is_active' => '1','parent_id' => '148','created_by' => NULL),
            array('name' => 'Surgical Extractions','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Hair Transplant Surgeon','description' => '<h2 style="text-align: justify;"><strong>About Hair Transplant Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is a medical specialty that makes use of manual machinery and instrumental techniques on a person to either find out or treat a disease or injury, to help better the body&rsquo;s function, appearance, or to repair unwanted ruptured areas. Hair transplant surgery or hair transplantation is a minimally invasive surgery technique where hair follicles from one part of the body are removed to a bald or balding part of the body. It&rsquo;s usually used to treat male pattern baldness.Surgeons must first obtain a bachelor&rsquo;s degree from an accredited university and while there&rsquo;s not a specific major that&rsquo;s needed to become a surgeon, most people will choose to study science, others may choose to study biology, health science, chemistry, kinesiology, and physics. While in university, they need to fulfill pre-med course requirements, gain experience, and complete their MCAT to get into medical school. Once finished with med school, the student will have to take a course in hair restoration and transplant training to become a certified specialist in hair transplant surgery.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A hair transplant surgeon can use their skills to restore eyelashes, eyebrows, beard hair, chest hair, pubic hair and to also fill in scars that were caused by accidents or from any other reason. There are three main types of surgery that hair transplant surgeons use and that is androgenetic alopecia, eyebrow transplant, and frontal hairline lowering or reconstruction.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of Hair Transplant Surgeons but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best Hair Transplant Surgeon in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Hair Transplant surgery (FUE)','description' => NULL,'is_active' => '0','parent_id' => '199','created_by' => NULL),
            array('name' => 'PRP (Platelet Rich Plasma Therapy)','description' => NULL,'is_active' => '1','parent_id' => '199','created_by' => NULL),
            array('name' => 'Mesotherapy','description' => NULL,'is_active' => '1','parent_id' => '199','created_by' => NULL),
            array('name' => 'ENT Surgeries','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => NULL),
            array('name' => 'Laser Specialist, Dermatologist, Cosmetologist','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'General Physician','description' => '<h2 style="text-align: justify;"><strong>About General Physician&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A general physician (GP) is a specialist who&rsquo;s highly trained and provides a large range of non-surgical healthcare to patients of all ages, usually adults, by giving them more preventative care with health education. They can treat both acute and chronic illnesses but for the most part are concerned in more difficult, serious, or unusual medical problems continuing to see the patient in their care until the problems have been resolved or stabilised. The importance of a GP varies from country to country as do the requirements to become one. In the United Kingdom for example, the individual must take at least 5 years of training after medical school where they then obtain their bachelor of medicine degree and their bachelor of surgery degree.&nbsp;&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">General physicians are mostly referred to by other doctors, in most cases by the patient&rsquo;s general practitioner. With every one of their patients, whether it be one or health problems the doctor tells them about, a GP&rsquo;s assessment is always comprehensive allowing any other problems and possibilities to be detected that were missed out on previously. As mentioned before, GP&rsquo;s are trained to care for both simple and complex illnesses even where diagnosis may be difficult. With their broad training and variation in knowledge, they can recommend the right treatment for their patients while also taking into consideration the social and psychological impact that they may be facing from the disease. General physicians, while not qualified to perform surgery, are integral in making sure that the patient is reviewed beforehand to assess their risk status and recommend the right management to reduce risk of the operation being performed. Even after that has taken place, postoperative, the GP can assist with ongoing medical problems or complications.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of General Physicians but they can be differentiated with the amount of experience they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best General Physicians in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Endoscopy','description' => '<h2 style="text-align: justify;"><strong>What is Endoscopy?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Endoscopy is a surgical procedure used to closely examine the upper digestive system. A tiny camera is attached to the end of a long flexible tube and inserted into the patient to get a visual within the body. With this, a digestive system specialist can diagnose and even treat conditions. It may help investigate the causes of symptoms like vomiting, nausea, and pain. Special tools attached to the endoscopy can remove foreign objects and widen narrow esophagus&rsquo;.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Those that will go through endoscopy will have to stop drinking and eating 8 hours prior to the procedure, stop taking any blood thinning medication such as aspirin a few days before, and if the patient is a diabetic, has high bp or a heart disease, the doctor will recommend instructions accordingly. After the procedure, the patient will stay in for an hour so allowing the sedative to wear off. Then they must go home and rest for a full day so that there&rsquo;s full recovery. There can be uncomfortable symptoms such as cramping, sore throat, bloating and gas. The results depend on the reason for the test. If was to find an ulcer for example, then it&rsquo;ll be known after the procedure but biopsy results take a few weeks.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Tuberculosis','description' => '<h2 style="text-align: justify;"><strong>What is Tuberculosis?</strong><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Tuberculosis is a disease caused by Mycobacterium tuberculosis, bacteria that attacks the lungs and also affects other body parts like the brain and spine.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">TB is a serious contagious infection. There are two types of TB infection.</span></p>
          <ul style="text-align: justify;">
          <li><strong>Latent TB</strong></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">In this condition, germs reside in the body but don&rsquo;t produce any symptoms and a person isn&rsquo;t contagious. The infection is alive in the body and can reactivate any time.&nbsp;</span><span style="font-weight: 400;">This is treated by antibiotics so that the risk of developing active TB is lowered.</span></p>
          <ul style="text-align: justify;">
          <li><strong>Active TB</strong></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">In this condition, germs multiply and infection occurs making you sick and you also become contagious and can spread the disease.</span></p>
          <h2 style="text-align: justify;"><span style="font-weight: 400;">&nbsp;</span><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms of active TB disease include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Persistent cough that lasts three weeks or more.</span></li>
          <li><span style="font-weight: 400;">Blood in cough</span></li>
          <li><span style="font-weight: 400;">Chest pains</span></li>
          <li><span style="font-weight: 400;">Weight loss</span></li>
          <li><span style="font-weight: 400;">Fever</span></li>
          <li><span style="font-weight: 400;">Loss of appetite</span></li>
          <li><span style="font-weight: 400;">Night sweating</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">TB can affect your other body parts too. When TB occurs in the spine, it gives back pain and when it occurs in the kidney, it may cause blood in the urine.</span></p>
          <h2 style="text-align: justify;"><strong>Who is at risk?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Some people have increased risks for TB</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">People with HIV/AIDS</span></li>
          <li><span style="font-weight: 400;">Intravenous drug users</span></li>
          <li><span style="font-weight: 400;">Were in contact with infected individuals</span></li>
          <li><span style="font-weight: 400;">Live and work in an environment where TB is prevalent</span></li>
          <li><span style="font-weight: 400;">Healthcare workers treating patients with TB</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Prevention and Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">If someone tests positive for latent TB, he is advised to take a course of medications to prevent the risk of developing active TB infection.&nbsp;</span><span style="font-weight: 400;">Countries where TB is common, BSG vaccine is given to children to prevent severe TB. Consult the best pulm</span></p>
          <p style="text-align: justify;"><strong>&nbsp;</strong></p>','is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Lumbar Puncture','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Glaucoma','description' => '<h2 style="text-align: justify;"><strong>What is Glaucoma?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Glaucoma is a serious condition in which the optic nerve of eyes is damaged. This condition worsens over time. This condition occurs due to build up pressure inside the eye. The increased pressure inside the eyes damages the optic nerve. The optic nerve is involved in sending images to the brain. If the condition worsens, permanent vision loss can happen.&nbsp;</span><span style="font-weight: 400;">If a person loses his vision, it can&rsquo;t be brought back.</span></p>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Blockage of cannel that carries aqueous humor</span></li>
          <li><span style="font-weight: 400;">Blunt or chemical injury to the eye</span></li>
          <li><span style="font-weight: 400;">Severe eye infection</span></li>
          <li><span style="font-weight: 400;">Inflammation in eyes</span></li>
          <li><span style="font-weight: 400;">Hereditary condition</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Following symptoms usually appear</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Seeing light halos</span></li>
          <li><span style="font-weight: 400;">Vision loss</span></li>
          <li><span style="font-weight: 400;">Increased redness in the eye</span></li>
          <li><span style="font-weight: 400;">Hazy eyes</span></li>
          <li><span style="font-weight: 400;">Pain in eyes</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Following treatment options are used for glaucoma</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Eye drops</span></li>
          <li><span style="font-weight: 400;">Oral medications</span></li>
          <li><span style="font-weight: 400;">Laser surgery</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Consult doctors when symptoms appear right away. Don&rsquo;t delay because when this condition worsens, permanent blindness occurs. You can consult with best dermatologist through the HospitALL app or website.&nbsp;&nbsp;</span></p>','is_active' => '1','parent_id' => '147','created_by' => NULL),
            array('name' => 'Dysmenorrhea','description' => '<h2 style="text-align: justify;"><strong>What is Dysmenorrhea?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Dysmenorrhea is another word for menstrual cramps. These occur when a woman goes through her menstrual cycle. They feel pressure and aching pain in the belly and pain in hips, lower back, and inner thighs. In severe cases, it can cause an upset stomach, loose stools, and sometimes vomiting.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">It is recommended to take pain relievers such as aspirin to ease pain.&nbsp; Women may also warm up the stomach by using a heating pad or hot water bottle on the lower back or stomach, a hot shower can help too. The woman should rest when needed, avoid anything with caffeine and salt, not to consume alcohol or use tobacco and to massage the abdomen and lower back. If the above does not relieve pain, then it&rsquo;s best to let the doctor know, they can prescribe further medication or give further advice.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best women&rsquo;s specialists available.</span></p>','is_active' => '1','parent_id' => '143','created_by' => NULL),
            array('name' => 'MRI','description' => '<h2 style="text-align: justify;"><strong>What is MRI?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A magnetic resonance imaging (MRI) globe is a typical technique around the globe.&nbsp;</span><span style="font-weight: 400;">MRI utilizes a solid magnetic field and radio waves to make detailed pictures of the organs and tissues inside the body.&nbsp;</span><span style="font-weight: 400;">Since its development, specialists and scientists keep on refining MRI strategies to aid clinical techniques and research. The advancement of MRI reformed medication.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">It is a non-invasive and painless procedure</span></li>
          <li><span style="font-weight: 400;">MRI scan is different from CT Scans and X-rays because it doesn&rsquo;t use harmful ionizing radiation.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Uses</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">It is used for examining the body in high detail. MRI scanner is used for the following</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Brain and spinal cord anomalies</span></li>
          <li><span style="font-weight: 400;">Tumors and cysts in various parts of the body</span></li>
          <li><span style="font-weight: 400;">Breast cancer screening</span></li>
          <li><span style="font-weight: 400;">Certain types of heart problems</span></li>
          <li><span style="font-weight: 400;">Liver and other abdominal diseases</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Preparation</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Very little preparation is required for an MRI scan. On arrival at hospital, you will be required to change in a gown. All the metal jewelry and accessories are removed so that they don&rsquo;t interfere with the machine.&nbsp;</span><span style="font-weight: 400;">People who are claustrophobic are given medications to make them more comfortable.</span></p>','is_active' => '1','parent_id' => '150','created_by' => NULL),
            array('name' => 'Anesthesia','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Anxiety disorders','description' => '<h2 style="text-align: justify;"><strong>What are Anxiety Disorders?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">While anxiety is a normal feeling, anxiety disorders are not. They are mental disorders that can stop someone from carrying on with a normal life. Those that have one, live in constantly overwhelming fear and worry which can be disabling. Causes of anxiety disorders come from a variation of things such as changes in the brain, environmental stress and even a person&rsquo;s genes. Disorders include panic disorder where the feeling of terror strikes randomly causing a panic attack, social anxiety, phobias that trigger anxiety, and general anxiety disorder.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Those with an anxiety disorder may have medication such as antidepressants or they may be treated with psychotherapy where they are counseled to address the emotional response to their mental illness.&nbsp;&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best therapists and anxiety specialists.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '138','created_by' => NULL),
            array('name' => 'Anesthesiologist','description' => '<h2 style="text-align: justify;"><strong>About Anesthesiologist&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An anesthesiologist is a doctor who is qualified and certified to practice anesthesia. Their job is concerned with taking care of patients before, during, and after surgery. It requires not only anesthesia, but also intensive care medicine, pain medicine, and critical emergency medicine. To become an anesthesiologist, the individual must first complete medical school to then acquire a medical degree. Then, they will have to go through a programme of postgraduate specialist training or residency which can go from four to nine years. Anesthesiologists train by gaining experience in many different subspecialties of anesthesiology and take many advanced postgraduate skill assessments and postgraduate examinations.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">In a nutshell, anesthesiologists make sure that patients undergoing surgery are safe throughout the whole procedure. They give care so that the patient experiences minimal pain and distress where they would otherwise experience immense amounts. This can be done by them giving patients anesthesia to put them to sleep, sedation where they give the patient medication to make them calm and/or unaware, or regional anesthesia where injections with local anesthesia are used near nerves to numb up the part of the body being operated on stopping pain from occurring. Without anesthesiologists, patients cannot go through surgeries without feeling a lot of pain and discomfort. This not only gets them through the surgery as comfortable as possible but also stops any risk of heart attacks and any other postoperative complications. Anesthesiologists must also be present during the operation, as mentioned before, because the patient&rsquo;s status needs to be constantly monitored i.e heart rate, blood pressure, breathing, and level of awareness during sedation, to be able to make any changes needed as according to the situation.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of anesthesiologists.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best acupuncturists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p><span style="font-weight: 400;">&nbsp;&nbsp;&nbsp;&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Paediatrics','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Preventive Medicine','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Pathology','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'General Anesthesia','description' => NULL,'is_active' => '1','parent_id' => '213','created_by' => NULL),
            array('name' => 'Laser Hair Removal','description' => '<h2 style="text-align: justify;"><strong>What is Laser Hair Removal?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Laser hair removal is one of the most common cosmetic procedures. The highly concentrated light is beamed into the hair follicles and the pigments absorb the light and this destroys the hair.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Laser hair removal is alternate to shaving, waxing and tweezing to remove the unwanted hair.</span></p>
          <h2 style="text-align: justify;"><strong>Benefits</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The laser is very useful to remove unwanted hair from the face, chin, back, arm, underarms, bikini line and other areas. The laser has many benefits</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Laser is very precise, targets the hair very selectively and leaves the skin undamaged.</span></li>
          <li><span style="font-weight: 400;">Pulse of the laser takes only a fraction of second making it a very speedy process.</span></li>
          <li><span style="font-weight: 400;">After three to seven sessions, patients experience permanent hair loss.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Preparing for laser</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Laser hair removal is a medical procedure and should be done by a professional and experienced practitioner.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Before going for the laser, limit waxing, plucking for six weeks prior treatment.</span></li>
          <li><span style="font-weight: 400;">Avoid sun exposure</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>During Laser treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before the procedure, hair is trimmed to a few mm above the skin surface. Numbing medicine is applied 20-30 minutes before the laser procedure. The laser equipment Is then set according to the thickness, location of the hair being treated and also the skin color.</span></p>
          <h2 style="text-align: justify;"><strong>Recovery</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">After two days, sin will look fresh. Use cool compressors and moisturizers to keep it hydrated. Over the next month, the treated hair falls out.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can wear makeup unless you form blisters on your skin.</span></p>
          <h2 style="text-align: justify;"><strong>Risks</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Risks that are commonly associated with laser are</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Blisters in people with a dark complexion</span></li>
          <li><span style="font-weight: 400;">Swelling</span></li>
          <li><span style="font-weight: 400;">Redness</span></li>
          <li><span style="font-weight: 400;">Scarring</span></li>
          <li><span style="font-weight: 400;">The permanent</span><span style="font-weight: 400;">&nbsp;change in skin is very rare.&nbsp;</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can consult the best plastic surgeon through the HospitALL app and website.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Paediatrics','description' => NULL,'is_active' => '1','parent_id' => '217','created_by' => NULL),
            array('name' => 'Acne Scar Removal','description' => '<h2 style="text-align: justify;"><strong>What is Acne Scar Removal?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Acne can often leave scars on the skin. This happens when a breakout goes deeply into the skin and damages the tissues beneath it. There are multiple types of acne scars where some are harder than others to get rid of. Treatments vary based on the severity of the acne scar.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">While there are a lot of self-treatments someone with an acne scar can use such as different creams and gels, it is best to visit a dermatologist as soon as possible so they can give the right treatment and evaluate how severe it is. They may prescribe medicine such as creams but for more serious cases, they may use laser, chemical peeling, dermabrasion, injections, microneedling and more.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best options for the removal of acne scars.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Skin Resurfacing','description' => NULL,'is_active' => '1','parent_id' => '204','created_by' => NULL),
            array('name' => 'Face uplift','description' => NULL,'is_active' => '0','parent_id' => '204','created_by' => NULL),
            array('name' => 'Double Chin,  Open Pores,  Wrinkles,  Dark Circles','description' => NULL,'is_active' => '0','parent_id' => '204','created_by' => NULL),
            array('name' => 'Body Contouring with Fat Reduction','description' => '<h2 style="text-align: justify;"><strong>What is Body Contouring?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Body contouring is a non-surgical fat reduction option. This involves reducing or removing stubborn pockets of fat to contour and shape different areas of the body. There are different ways to perform this. Cryolipolysis uses freezing temperatures to target and destroy fat cells, laser lipolysis uses controlled heating and laser energy to target fat cells, and injection lipolysis uses injectable deoxycholic acid to target fat cells.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The type of treatment that&rsquo;s done depends on what area is being targeted and on other factors. Side effects are short-term and mild with usually redness, swelling and pain. The procedure is minimal to non-invasive so hardly scarring if any and regular activity can be resumed immediately after the procedure.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best plastic surgeons and bariatrics.&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></p>','is_active' => '1','parent_id' => '32','created_by' => NULL),
            array('name' => 'Anti Aging Treatements','description' => '<h2 style="text-align: justify;"><strong>What is Anti-aging treatment?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL offers the best dermatologists/skin specialists for anti-ageing treatments deciding what treatment is most suitable according to your needs. These treatments can include injections, laser treatment, creams, medication, and plastic surgery.</span></p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Tattoo Removal   Birth Marks Removal','description' => NULL,'is_active' => '1','parent_id' => '204','created_by' => NULL),
            array('name' => 'C-Section','description' => '<h2 style="text-align: justify;"><strong>What is C-Section?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cesarean delivery, also known as a C-section is a surgical way to deliver a baby by the use of incisions in the abdomen and uterus. C-sections may be chosen prior to birth but the need for it the first time will not be obvious until labor starts. It&rsquo;s done if labor isn&rsquo;t moving along/is stalled, the baby is in distress, baby/babies are in an abnormal position, there is a current health concern in the carrier, etc.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Before the C-section, the patient will have their abdomen cleansed and will have any urine within them collected. Regional anesthesia, which affects the lower part of the body, will be given and the operation is done while conscious. During the procedure, an abdominal incision is made, followed by a uterine incision and then the delivery. After the C-section, the patient will stay at the hospital for a couple of days while being advised on pain relief options available for them by the doctor. Once they are ready to leave the hospital, they are advised to recover and rest as much as possible to heal. They must take it easy and use pain relief when necessary.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best maternal doctors and options.</span></p>','is_active' => '1','parent_id' => '143','created_by' => NULL),
            array('name' => 'Ultrasound','description' => '<h2 style="text-align: justify;"><strong>What is Ultrasound?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Ultrasound scan is a medical test that employs high-frequency sound waves that capture images from inside of our body.&nbsp;</span><span style="font-weight: 400;">Ultrasound is used to diagnose the underlying causes of pain, swellings, or any infection in the internal organs of the body. It is also used to examine the growth of the baby in pregnant women. It can also detect lumps in the body and can also reveal if the lump is cancerous or just a fluid-filled cyst.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Ultrasound is a safe procedure as it doesn&rsquo;t involve any incision and any ionizing radiation.&nbsp;</span><span style="font-weight: 400;">The image produced by ultrasound is called a sonogram and the image is interpreted by a radiologist.</span></p>
          <h2 style="text-align: justify;"><strong>How should you prepare for Ultrasound?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There&rsquo;s no special preparation for Ultrasound. Your doctor will guide you on how to prepare yourself for the ultrasound scanning. You may be asked to refrain from eating and drinking beforehand.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Wear loose and comfortable clothing while going for an ultrasound. The whole procedure lasts less than 30 minutes depending upon the area under examination and after the examination, you can carry out your daily tasks without any hindrance or issue.</span></p>','is_active' => '1','parent_id' => '150','created_by' => NULL),
            array('name' => 'Vaginal Delivery','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => NULL),
            array('name' => 'Coronary Angiography','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Angioplasty','description' => '<h2 style="text-align: justify;"><strong>What is Angioplasty?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Angioplasty is a surgical procedure to open clogged arteries in the heart. A tiny balloon catheter is inserted into the blocked blood vessel to help widen it thus increasing blood flow to the heart. It may be used if other medications or lifestyle changes have not improved heart health, chest pain is worsening or if there is a heart attack. It&rsquo;s not recommended for everyone and depends on the situation</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Once a patient has gone through with this procedure, provided it is non-emergency, they&rsquo;ll stay overnight at the hospital before being able to go to work the next week. The doctor will decide on the restrictions for a period of time but drinking plenty of water is needed to flush contrast dye out of the body. For medication, blood thinning medicine will need to be taken like aspirin. The doctor will decide which medicine is needed, the dosage and for how long depending on the situation.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best cardiologists available.&nbsp;&nbsp;</span></p>
          <p><br /><br /></p>','is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Echocardiography','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Exercise Treatment Testing','description' => NULL,'is_active' => '0','parent_id' => '130','created_by' => NULL),
            array('name' => 'Holter Cardiac Monitory','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Pacemaker Implantation','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Bridges and Implant placements','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Orthodontic Braces','description' => NULL,'is_active' => '1','parent_id' => '4','created_by' => NULL),
            array('name' => 'Preventive Diseases','description' => NULL,'is_active' => '1','parent_id' => '218','created_by' => NULL),
            array('name' => 'Musculoskeletal Trauma','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Pathology','description' => '<h2 style="text-align: justify;"><strong>What is Pathology?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pathology is a branch of medicine that is involved in treating the essential cause of disease. This diagnosis of the disease is done by examining organs, tissues and bodily fluids.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">General pathology is a term used to describe the practice of anatomic and clinical pathology.</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">Anatomic Pathology deals with the diagnosis of disease by examining the microscopic, chemical, molecular, the immunologic examination of organs, tissues and also in the autopsy.</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Clinical pathology is also called as laboratory medicine in which the diagnosis of the disease is done by examining urine and blood.</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Other types of pathology include forensic pathology and veterinary pathology</span></li>
          </ul>','is_active' => '1','parent_id' => '398','created_by' => NULL),
            array('name' => 'Autism','description' => '<h2 style="text-align: justify;"><strong>What is Autism?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Autism is a complex condition to do with problems in communication and behaviour. Depending on what spectrum the autism is on, it can be a minor problem or require full time care. Those that are diagnosed with it have trouble in communicating, understanding how others feel and think and have a hard time expressing themselves. They may have problems learning and so may have their skills develop unevenly and those that do tend to be good at art, music, math or memory. Autism is much more common in males than females. Different spectrums include asperger&rsquo;s, where children mainly have social problems with a narrow amount of interests, autistic disorder which is the form of autism most people refer to, and more. There is no main cause of it but it is to do with problems with parts of the brain which interpret sensory input and process language. Autism also runs in the family so a certain combination of genes increases the likelihood of getting it. Children with an older parent and a pregnant woman exposed to certain drugs or chemicals have a higher risk of having an autistic child.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">While there is no cure for autism, treatment can make a big difference for a child. Recognising autism early and telling the doctor as soon as possible would be best. The doctor will tailor treatment specifically for the child because every child will be different. Main treatments include behavioural and communication therapy, to help encourage positive behaviour and also discourage negative behaviour at an early age and the use of medication to help with the symptoms of autism such as anxiety, attention problems and hyperactivity.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors to treat autism.</span></p>','is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Brainstem Death','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Cerebral Abscess','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Cluster Headaches','description' => '<h2 style="text-align: justify;"><strong>What is a Cluster Headache?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cluster headaches are the least common type of headache. They are a number of quite short but very painful headaches that occur every day for weeks or even months at a time. They tend to always come around at the same time each year, during spring or fall for example and because of this, they are mistaken for allergies or stress. There hasn&rsquo;t been a direct cause found and they can stop and start whenever for any period of time. The sensation, severe pain around an eye, is so bad that those experiencing it cannot be still. A specific nerve pathway is activated by the hypothalamus and affects the trigeminal nerve; this is responsible for the pain felt. Cluster headaches are more common in men and seem to appear more in smokers and heavy drinkers.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Medication which stops or eases the pain is the main treatment option for cluster headaches. Though if the pain is constant and there are hardly breaks between headaches, then surgery is an option.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors to treat all kinds of headaches</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Learning Disability','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Stress Headaches','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Liposuction','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Nose Reshaping','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Eye Beaufication (Blepharoplasty)','description' => NULL,'is_active' => '1','parent_id' => '204','created_by' => NULL),
            array('name' => 'Arm Lift','description' => '<h2 style="text-align: justify;"><strong>What is an Arm Lift?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An arm lift is when a cosmetic surgical procedure is performed to make the appearance of the under portion of the upper arms look more appealing. This is done by removing excess skin and fat between the armpit and elbow. Risks that may occur after the surgery include permanent scarring, which are usually in areas that aren&rsquo;t easily visible, stitches that may work into the surface of the skin requiring more surgery to treat, temporary numbness, and imperfect symmetry. Arm lifts are not advised for the significantly overweight, those who have a constant change in their weight, smokers, and those that have a condition which interferes with their scar healing.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">After the surgery, lifting above the shoulder level must be avoided for a few weeks, physical and athletic activities that may stretch the incisions must also be avoided, take antibiotics to avoid infection and take pain medications if needed.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best plastic surgeons available.</span></p>','is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Gynaecomastia Treatment','description' => NULL,'is_active' => '0','parent_id' => '3','created_by' => NULL),
            array('name' => 'Chemical Peels','description' => NULL,'is_active' => '1','parent_id' => '3','created_by' => NULL),
            array('name' => 'Fillers and Botox Treatments','description' => NULL,'is_active' => '1','parent_id' => '204','created_by' => NULL),
            array('name' => 'Joint replacement surgery','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Spine surgery arthroscopy','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Advance AO fracture fixation techniques','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Liver Biopsies','description' => NULL,'is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Biliary Stone Treatment','description' => '<h2 style="text-align: justify;"><strong>About Biliary Stone treatment </strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Biliary Stone treatment is treatment for when gallstones move out of the gallbladder and then pass into the stomach. Gallstones are hard crystal-like pdescriptions made in the gallbladder by the bile. They can be very small like the size of a grain of salt or one large one. There may even be several thousand stones. Some people may have silent stones where they are symptom free for years and don&rsquo;t feel anything, requiring no treatment. But those who do get symptoms may feel sudden pain, chills, a fever, nausea, get jaundice and possibly start vomiting. This all may be separated by weeks, months, or years.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Gallstones are found through ultrasound or CT scans usually. For surgical treatment, the gallbladder is removed. This can either be done in an open cholecystectomy or in a laparoscopic cholecystectomy. With an open surgery, the gallbladder is removed through a 5-8 inch cut and the patient stays at the hospital for about a week before recovering at their home for several weeks. But the laparoscopic approach is used much more often as there is a much smaller cut hence resulting in less pain, quicker healing, less scarring and hardly a chance of infection. Recovery requires only one night in the hospital and several days resting at home. There are also non surgical treatments, but those are used in special circumstances.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best gastroenterologists and surgeons available.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Black Stools','description' => '<h2 style="text-align: justify;"><strong>What are Black Stools?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Black stools are when the feces of a person are dark and black, they can indicate bleeding or other injuries in the gastrointestinal tract. But black stools may also come out because of consuming dark-colored food such as blueberries, dark chocolate cookies, black licorice and more. Even still, it is best to tell a doctor as soon as this irregularity is spotted to rule out serious medical conditions.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">If it is serious, black stools primarily indicate bleeding so the doctor may prescribe acid-reducing medication to treat bleeding ulcers but treatment depends on what exactly is causing the black stools.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors available.</span></p>
          <p style="text-align: justify;"><br /><br /><br /><br /></p>','is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Digital Rectal Examination','description' => NULL,'is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Endoscopic Guided Ultrasound','description' => NULL,'is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Fissures','description' => NULL,'is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Gastroscopy','description' => NULL,'is_active' => '0','parent_id' => '139','created_by' => NULL),
            array('name' => 'Hemorrhoids','description' => '<h2 style="text-align: justify;"><strong>What are Hemorrhoids?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Piles also called hemorrhoids are inflamed and swollen tissues in the anal and rectal area.&nbsp;</span><span style="font-weight: 400;">Piles can develop inside the rectal area (internal hemorrhoids) or outside the skin around the anus (external hemorrhoids).&nbsp;</span><span style="font-weight: 400;">Hemorrhoids are a very common condition affecting three out of four adults once in their lifetime.</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms of hemorrhoids depend upon the type of hemorrhoid</span></p>
          <h2 style="text-align: justify;"><strong>External Hemorrhoids</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">In external hemorrhoids, a swollen outgrowth of tissues is around the anus. Symptoms include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Itching around the anal region</span></li>
          <li><span style="font-weight: 400;">Extreme pain and discomfort</span></li>
          <li><span style="font-weight: 400;">Swelling in the anal region</span></li>
          <li><span style="font-weight: 400;">Bleeding</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Internal Hemorrhoids</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Internal hemorrhoids occur inside the rectal area and it is not visible outside. Symptoms include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Painless bleeding while passing stool</span></li>
          <li><span style="font-weight: 400;">Protruding hemorrhoid results in pain and irritation</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Exact causes of piles are still not clear, but this may occur due to</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Chronic constipation</span></li>
          <li><span style="font-weight: 400;">Chronic Diarrhea</span></li>
          <li><span style="font-weight: 400;">Pregnancy</span></li>
          <li><span style="font-weight: 400;">Increased pressure in the lower rectum</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Piles can resolve on its own without the need for extensive treatment. Some treatment option include</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">&nbsp;</span><span style="font-weight: 400;">Lifestyle changes including dietary change and weight loss</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">&nbsp;</span><span style="font-weight: 400;">Increased water consumption</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">&nbsp;M</span><span style="font-weight: 400;">edication and laxatives</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;"> &nbsp;</span><span style="font-weight: 400;">Surgical options&nbsp;</span></li>
          </ul>','is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Manometry','description' => NULL,'is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'Non-Invasive Orthopedic Surgery','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Eye Surgeon','description' => NULL,'is_active' => '0','parent_id' => '147','created_by' => NULL),
            array('name' => 'Oncologist','description' => '<h2 style="text-align: justify;"><strong>About Oncologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A&nbsp; doctor who specializes in preventing, diagnosing, and treating cancer is an&nbsp;</span><strong>oncologist</strong><span style="font-weight: 400;">.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The main types of oncologist are:</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Medical oncologist.</span></li>
          <li><span style="font-weight: 400;">Radiation oncologist.</span></li>
          <li><span style="font-weight: 400;">Surgical oncologist.</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Usually, oncologists will supervise your general care and manage treatments with other specialists. Your oncologist will also be the supervisor of chemotherapy, immunotherapy, hormone therapy. You possibly have to visit your medical oncologist for long-term, regular checkups. They also treat cancer by removing tumors or other cancerous tissue.</span></p>
          <p style="text-align: justify;"><strong>Medical oncologist</strong><span style="font-weight: 400;">: A medical oncologist is the cancer specialist you&rsquo;ll encounter most often.</span></p>
          <p style="text-align: justify;"><strong>Radiation oncologist: </strong><span style="font-weight: 400;">Oncologists that treat cancer with radiation therapy.</span></p>
          <p style="text-align: justify;"><strong>Surgical oncologist: </strong><span style="font-weight: 400;">A&nbsp; surgeon who has particular training in treating cancer. He might be called in to diagnose cancer with a biopsy.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cancer is a serious medical condition that requires special care and also extensive treatment. Before getting started with cancer treatments, search the best oncologist for yourself. You can book an instant appointment with the best oncologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Acute kidney failure','description' => '<h2 style="text-align: justify;"><strong>What is Acute Kidney Failure?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Acute Kidney Failure is when the kidneys suddenly lose the ability to eliminate fluids, waste materials and excess salts. Which are essentially the kidneys&rsquo; main functions, to filter the body. Due to this, body fluids can increase to dangerous levels. It will also cause electrolytes and waste material to accumulate in the body. Both these symptoms are life-threatening. Causes of it include severe or sudden dehydration, acute tubular necrosis (ATN) which is damage to the tubes within the kidney that carry excess salts and waste materials, kidney injury from poisons or certain medications, and more.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Though it is life-threatening, those in good health can recover with it becoming reversible. Treatment options include an assigned diet plan which would include less liquids, more carbohydrates, less protein, salt and potassium. There is medication that can be prescribed by the doctor to prevent infections and help the kidney perform its main functions. Dialysis may be necessary and it will most likely be temporary. In severe cases, it may become permanent.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors for kidney related problems.&nbsp;</span></p>','is_active' => '1','parent_id' => '127','created_by' => NULL),
            array('name' => 'Haemodialysis','description' => NULL,'is_active' => '1','parent_id' => '127','created_by' => NULL),
            array('name' => 'ETT (Exercise Tolerance Test)','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Stenosis and Valvular Regurgitation','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Stress echocardiography','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'trans-esophageal Echocardiography','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'trans-thoracic Echocardiograpghy','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'ENT / Otolaryngology specialist','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Endoscopic Sinus Surgery','description' => NULL,'is_active' => '1','parent_id' => '280','created_by' => NULL),
            array('name' => 'Laryngology','description' => NULL,'is_active' => '1','parent_id' => '280','created_by' => NULL),
            array('name' => 'blood Pressure','description' => '<h2 style="text-align: justify;"><strong>What is Blood Pressure?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Blood Pressure (BP) is the measure of the force of blood pushing against blood vessel walls. The heart pumps blood into the blood vessels which transport the blood around the whole body. A high BP is dangerous as it makes the heart work harder to pump blood around the body. This is dangerous because it causes the arteries to harden, and can result in stroke, kidney disease, and to heart failure. Reasons for a high BP include obesity, smoking, genetics, older age, stress, lack of physical activity, sleep apnea and more.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">To lower down BP, it&rsquo;s recommended to make lifestyle changes such as eating a more heart-healthy diet with less salt, regular physical activity, and maintaining a good weight. But lifestyle changes may not be enough, so medication will be needed and the medication prescribed will depend on person to person.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors to treat high blood pressure.</span></p>','is_active' => '1','parent_id' => '112','created_by' => NULL),
            array('name' => 'Cholesterol','description' => '<h2 style="text-align: justify;"><strong>What is Cholesterol?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cholesterol is a substance present within blood. The body requires it to build healthy cells, however, too much of it can run the risk of heart disease. This is because a high amount causes the development of fatty deposits in the blood vessels. With time, these may grow making it harder for a sufficient amount of blood to flow through the arteries. The deposits could even break suddenly to form a clot that causes heart attack or stroke. High cholesterol is something that&rsquo;s inheritable but usually it appears because of an unhealthy lifestyle. This does mean it is preventable and also treatable. It is mainly caused by lipoproteins, these are a combination of proteins and cholesterol. Low-density lipoprotein (LDL) is bad cholesterol and too much of it may build up on the walls of the arteries making them hard and narrow while too little of high-density lipoprotein (HDL) will not transport enough cholesterol to the liver causing a build up. Factors that cause high cholesterol are diabetes, age, obesity, poor diet, smoking and lack of exercise.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">If lifestyle changes do not help and there is still high cholesterol, there are many options for medication that the doctor can prescribe, which depends on what is causing the high cholesterol and other factors such as age, health, etc.. Medication include statins, bile-acid-binding resins, cholesterol absorption inhibitors, and injectable medications which all lower cholesterol levels. There are more medications the doctor may list for other options.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best heart doctors and the best doctors for cholesterol treatment.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '112','created_by' => NULL),
            array('name' => 'Thyroid','description' => NULL,'is_active' => '1','parent_id' => '112','created_by' => NULL),
            array('name' => 'Endocrine disorders','description' => NULL,'is_active' => '1','parent_id' => '112','created_by' => NULL),
            array('name' => 'General Medicine','description' => NULL,'is_active' => '1','parent_id' => '112','created_by' => NULL),
            array('name' => 'Early Arthritis diagnosis and management','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Osteoporosis screening and management','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Psoriatic Arthritis','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Ankylosing Spondylitis','description' => '<h2 style="text-align: justify;"><strong>What is Ankylosing Spondylitis?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Ankylosing Spondylitis is a rare form of arthritis. It is inflammatory and can over time have the small bones in the spine fuse.&nbsp; It causes pain and stiffness in the spine and is also a lifelong disease usually starting on the lower back. It can reach up to the neck or damage joints in another part of the body. A severe case of this would result in a permanently hunched back.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">This is an incurable disease, but certain medication may ease the pain and swelling and/or delay more spinal problems. A healthy diet along with exercise would go a long way in allowing better movement and also help stop the condition from worsening. The type of medications the patient will receive will depend on how bad the condition of the patient is hence why a doctor should be seen as quickly as possible.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best orthopedics/back doctors.</span></p>','is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Polymyalgia Rheumatic','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Gout','description' => '<h2 style="text-align: justify;"><strong>What is Gout?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Gout is a common, but complex form of arthritis that affects anyone. Gout is characterized by swelling, redness and pain in joints especially in the base of the big toe.&nbsp;</span><span style="font-weight: 400;">An attack of gout occurs suddenly, waking you up in the middle of the night with a sharp piercing pain in your big toe.&nbsp;</span><span style="font-weight: 400;">The affected joint is hot, swollen and so tender that even the slightest weight can be tolerated.</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Following are the symptoms of gout</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Intense pain in joints</span></li>
          <li><span style="font-weight: 400;">Persistent discomfort</span></li>
          <li><span style="font-weight: 400;">Inflammation &amp; Redness</span></li>
          <li><span style="font-weight: 400;">Restriction in motion</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Gout occurs when urate crystals get accumulated in joints causing inflammation and intense pain. Urate crystals form when uric acid gets high in the blood. Normally, the body has the ability to dissolve uric acid in the blood and then passes it in kidneys, but sometimes when uric acid is in excess; it builds up inside the body and gets accumulated in joints.</span></p>
          <h2 style="text-align: justify;"><strong>Risk Factors</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Following are the risk factors that increase your chances of getting gout</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Diet rich in uric acid</span></li>
          <li><span style="font-weight: 400;">Obesity</span></li>
          <li><span style="font-weight: 400;">Medical conditions like diabetes, metabolic syndromes and kidney diseases</span></li>
          <li><span style="font-weight: 400;">Family history</span></li>
          <li><span style="font-weight: 400;">Certain medications</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Prevention</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Following preventions can be taken</span></p>
          <ul>
          <li style="text-align: justify;"><span style="font-weight: 400;">Drink plenty of fluids</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Limit alcohol use</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Acquire protein from low-dairy products</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Limit intake of meat</span></li>
          <li style="text-align: justify;"><span style="font-weight: 400;">Lose weight</span></li>
          </ul>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Osteoarthritis','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Lupus','description' => '<h2 style="text-align: justify;"><strong>What is Lupus?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Lupus is an autoimmune disease that occurs when the body&rsquo;s own immune system starts attacking the tissues and organs. The inflammation resulting from lupus affects different body parts like skin, blood cells, brain, heart, lungs and kidneys.&nbsp;</span><span style="font-weight: 400;">It is a long term systematic autoimmune condition.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are different kinds of lupus, but the most important and familiar type is Systemic lupus erythematosus. Other types are discoid, neonatal and drug-induced.</span></p>
          <p style="text-align: justify;"><strong>Systemic lupus erythematosus</strong></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">SLE is a systemic condition and can affect the whole body and is most severe than the other types. It causes inflammation in the skin, joints, blood, heart, and kidneys.</span></p>
          <p style="text-align: justify;"><strong>Discoid lupus erythematosus</strong></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">In this only skin is affected and a rash appears on the face, neck, and scalp. DLE doesn&rsquo;t affect internal organs, but people with DLE often develop SLE later in life.</span></p>
          <p style="text-align: justify;"><strong>Drug-induced lupus</strong></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">This occurs due to a reaction to a certain type of prescription drug. Drugs that are commonly associated with this type of lupus are</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Hypertension medication</span></li>
          <li><span style="font-weight: 400;">Arrhythmia medication</span></li>
          <li><span style="font-weight: 400;">Certain type of antibiotic that treats Tuberculosis</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms usually go away when the person stops taking medications.</span></p>
          <p style="text-align: justify;"><strong>Neonatal Lupus</strong></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">1% of women with SLE give birth to a baby with neonatal lupus. At birth babies with neonatal lupus have a rash on skin, liver problems with low blood count.</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms of lupus occur when the condition is flared-up. It has a wide range of symptoms including</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Fatigue</span></li>
          <li><span style="font-weight: 400;">Weight loss and loss of appetite</span></li>
          <li><span style="font-weight: 400;">Pain and swelling in joints</span></li>
          <li><span style="font-weight: 400;">Swollen glands</span></li>
          <li><span style="font-weight: 400;">Swollen lymph nodes</span></li>
          <li><span style="font-weight: 400;">Skin rashes</span></li>
          <li><span style="font-weight: 400;">Fever</span></li>
          <li><span style="font-weight: 400;">Mouth ulcer</span></li>
          <li><span style="font-weight: 400;">Chest pain during breathing</span></li>
          <li><span style="font-weight: 400;">Arthritis</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There is as such no cure for lupus but people can manage their symptoms through medications and lifestyle changes.</span></p>','is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Connective Tissue Disorders','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Vasculitis','description' => '<h2 style="text-align: justify;"><strong>What is Vasculitis?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The inflammation caused by blood vessels is termed as vasculitis. This happens when the immune system attacks the blood vessels. This may happen due to infection, the side effects of any medicine or some other disease.&nbsp;</span><span style="font-weight: 400;">The inflammation can occur in arteries, vein and capillaries.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">When blood vessels are inflamed</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">They become narrow and constricted making it more difficult for the blood to get through</span></li>
          <li><span style="font-weight: 400;">Close off and blood cant pass-through</span></li>
          <li><span style="font-weight: 400;">A bulge called aneurysm is formed which can cause internal bleeding. </span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms of vasculitis may vary but general symptoms include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Fever</span></li>
          <li><span style="font-weight: 400;">Swelling</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The main purpose of treating vasculitis is to stop the inflammation in the blood vessels.&nbsp;</span><span style="font-weight: 400;">Steroids and medicines are used to stop and prevent inflammation.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">&nbsp;</span></p>','is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Fibromyalgia','description' => '<h2 style="text-align: justify;"><strong>What is Fibromyalgia?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Fibromyalgia is a chronic medical condition that is most common in women. It affects the bones and muscles causing pain and fatigue within the muscle. In addition to that, the patient may have a foggy mind and may have memory lapses. While there&rsquo;s no cure, the symptoms can be eased by having medication, exercising, reducing stress, and adopting healthy habits. Doctors do not know the direct cause for it but those who are most likely to get fibromyalgia are women, have another pain-related disease such as arthritis, have anxiety, depression or a mood disorder, PTSD, very rarely exercise, and if other family members have it.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pain relief medication like aspirin, antidepressants, muscle relaxers, and drugs to help sleep are prescribed. The doctor also advises adopting a more healthy lifestyle by regularly doing moderate exercise such as yoga, walking, etc&hellip;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors to help with fibromyalgia.</span></p>','is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Soft tissue rheumatism and pain disorders','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Nerve Pain','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Neck Pain','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Back Pain','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Prostate','description' => NULL,'is_active' => '0','parent_id' => '105','created_by' => NULL),
            array('name' => 'PCNL (Percutaneous nephrolithotomy)','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Laser stone surgery','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Minimally invasive surgery','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Microscopic surgery','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Urological tumor surgery','description' => NULL,'is_active' => '1','parent_id' => '105','created_by' => NULL),
            array('name' => 'Wheezing','description' => '<h2 style="text-align: justify;"><strong>What is Wheezing?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Wheezing is a high pitched whistling sound that occurs while someone breathes. It is heard usually when someone exhales and in extreme cases, it is also heard when someone inhales in too.&nbsp;</span><span style="font-weight: 400;">This is caused by inflammation and narrowed airways.&nbsp;</span><span style="font-weight: 400;">Wheezing may also indicate a serious underlying breathing issue that requires immediate diagnosis and treatment.</span></p>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The most common causes of wheezing are Asthma and Chronic obstructive pulmonary disease (COPD).&nbsp;</span><span style="font-weight: 400;">There are many other causes of wheezing too. A right pulmonologist can determine the reason.</span></p>
          <h2 style="text-align: justify;"><strong>Seeking medical help</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You should seek medical help when you experience wheezing for the first time. The doctor will determine if you are wheezing or having any other breathing issue.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Treatment can be done in two ways</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;"> &nbsp; &nbsp; &nbsp; &nbsp; </span><span style="font-weight: 400;">Either by controlling the inflammation in airways</span></li>
          <li><span style="font-weight: 400;"> &nbsp; &nbsp; &nbsp; &nbsp; </span><span style="font-weight: 400;">Or by opening up the breathing tubes with medications</span></li>
          </ul>
          <p style="text-align: justify;"><span style="font-weight: 400;">A pulmonologist will determine the right option for you. Consult with the best</span><span style="font-weight: 400;"> pulmonologist near you</span><span style="font-weight: 400;"> for the right diagnosis and treatment.</span></p>','is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Rhinitis & respiratory allergies','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Pneumonia after acute stage','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Mucous plugs','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Mucosal edema','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Frequent acute disorders of respiratory tract','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Bronchoscopy','description' => '<h2 style="text-align: justify;"><strong>What is Bronchoscopy?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Bronchoscopy is a procedure used by doctors to look at the lungs and air passages of a patient. It is done to see why there is persistent coughing, for example, to see an infection or why there is something unusual seen on a chest X-ray or test. Bronchoscopy may also be done to obtain samples of mucus or tissue or to provide treatment for lung problems by removing any blockages. Risks of bronchoscopy include minor bleeding, fever and in very rare cases a collapsed lung.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Several days before the procedure, the doctors may ask the patient to stop taking any blood-thinning medication and to not eat or drink four-eight hours before the procedure. The procedure itself takes 30-60 minutes. A thin tube (bronchoscope) is passed through the nose or mouth, down the throat and into the lungs. The bronchoscope has a light and a very small camera at its tip which displays pictures on a monitor to help guide the doctor. Samples of tissue and fluid might be taken, the patient is not meant to feel much pain, if any at all, during the whole procedure as many measures are taken to prevent the patient from feeling any discomfort. Afterward, there will be monitoring for several hours, the throat and mouth will be numb for a few hours so nothing can be eaten or drunk to prevent liquids or solids from entering the lungs or any other airways. After the numbness wears off, water to soft foods can be eaten and drunk. There may be a mild sore throat, cough, muscle aches or hoarseness which is normal and will wear off eventually. The results will be discussed 1-3 days later and if a biopsy is done during the procedure, that will take longer.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best lung doctors and doctors for a bronchoscopy procedure.&nbsp;</span></p>','is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Breathlessness and Allergies','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Cough With Viscous Sputum','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Arterial line placement','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Central venous catheterization (through IJV, subclavian, brachial and femoral venous routs)','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Temporary pacing','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Endotrachial intubation','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Peritoneal paracentises','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Lumbar puncture','description' => NULL,'is_active' => '0','parent_id' => '149','created_by' => NULL),
            array('name' => 'Thoracocentesis','description' => NULL,'is_active' => '1','parent_id' => '149','created_by' => NULL),
            array('name' => 'Brain Tumor','description' => '<h2 style="text-align: justify;"><strong>What is a brain tumor?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A brain tumor is the large growth of abnormal cells within the brain. Some brain tumors are noncancerous while others are cancerous. Brain tumors can either start right from the brain, primary brain tumors, or cancer can start from one part of the body and then eventually reach the brain, secondary brain tumors. Primary brain tumors start when normal cells get mutations in their DNA which causes them to grow and divide at increased rates; they continue to live instead of dying like healthy cells, this creates a mass of abnormal cells forming a tumor. Secondary brain tumors are much more common than primary brain tumors in adults and usually occur in people who have a history of cancer. Breast cancer, kidney cancer, lung cancer, colon cancer and melanoma are the usual causes of secondary brain tumors.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is the usual treatment for brain tumors. If they&rsquo;re small and easy to seperate, all of the tumor can be removed. In other cases if tumors are harder to seperate or are located in a sensitive area, the surgeon will remove as much as they can to reduce symptoms. The risks of removing a tumor can be high with infection and bleeding possible. If the tumor is located near nerves that connect to the eyes, it can result in vision loss. Other treatment options include radiation therapy, radiosurgery and chemotherapy. The doctor will decide as to what procedure is best based on the situation. After the surgery, rehabilitation may be needed in a certain area such as physical therapy, speech therapy, occupational therapy, or therapy for young children to help cope with their changes.</span></p>
          <p style="text-align: justify;">&nbsp;</p>
          <p><span style="font-weight: 400;">HospitALL provides the best brain doctors and surgeons</span></p>','is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'DYSPHAHGIA','description' => NULL,'is_active' => '1','parent_id' => '219','created_by' => NULL),
            array('name' => 'STAMMERING','description' => NULL,'is_active' => '1','parent_id' => '219','created_by' => NULL),
            array('name' => 'PUBOPHONIA','description' => NULL,'is_active' => '1','parent_id' => '219','created_by' => NULL),
            array('name' => 'CEREBRAL PALSY','description' => '<h2 style="text-align: justify;"><strong>What is a Cerebral palsy?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cerebral palsy (CP) is a group of disorders that mainly affect movement and posture of a person. It&rsquo;s due to damage on an immature brain as it develops, usually before birth. This can be because of alcohol, infection, head injury, etc&hellip; The signs and symptoms of CP become apparent in the infant or preschool years. Those with CP have impaired movement like abnormal reflexes and posture, imbalanced walking, uncontrollable movement and floppiness. They may also have problem swallowing and usually have eye muscle imbalance where the eyes don&rsquo;t focus on the same object. There&rsquo;s a reduced amount of motion at various joints because of muscle stiffness. Some with CP can walk while others need assistance, some people have normal intellect while others have disabilities in intellect. Epilepsy, blindness or deafness are possibilities too.&nbsp;&nbsp;&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Though CP cannot be fully cured, the effects can be reduced and the child can be led to live a normal life. Medications that lessen muscle tightness may be prescribed by doctors to better functional abilities and treat pain. These include muscle/nerve injections and oral muscle relaxants which there are many of; they are given accordingly to what part of CP is being treated. Many different therapies play a part in helping the child through cerebral palsy. Physical therapy helps with muscle training and the child&rsquo;s strength, occupational therapy helps the child gain independence in everyday life and speech and language therapy improves their ability to talk. Recreational therapy is also an option to give the child sports to play or assign activities like skiing or horse riding for their motor skills, mental wellbeing and speech. Surgery may also be used to place bones in their correct positions if there are severe deformities.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best children and cerebral palsy specialists.</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Cerebral Herniation','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Craniectomy','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Carniotomy','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Head Trauma','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Hemorrhage','description' => '<h2 style="text-align: justify;"><strong>What is Hemorrhage?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Hemorrhage is another word for bleeding or blood loss. This can be internal bleeding or external bleeding. Internal bleeding happens when the blood comes out from a damaged blood vessel or organ and external bleeding occurs when there&rsquo;s a leak of blood because of a break in the skin. Bleeding may happen in practically any part of the body. The most obvious openings it can occur are the mouth, nose, rectum, and in women, the vagina. Blood loss can be caused by a variety of things like cuts (lacerations), gunshot wounds, serious injuries, punctures into the skin from needles or penetrating items, and bruises. Medical conditions like brain trauma, lung cancer, liver disease, leukemia, and other similar problems. In addition to that, medicines may also cause bleeding or increase the chances of it like blood thinners, antibiotics, etc.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The treatment will depend on how severe the bleeding is. If it is a penetrating object like a knife, arrow, or any other weapon on the wound, they must stay where they are because if removed, it may cause more harm and more bleeding. It&rsquo;s best to put a bandage and/or pads to keep it in place and absorb the bleeding. For less dangerous situations apply medium pressure with items like a clean cloth, bandage(s), hands with gloves on, to slow and stop the bleeding.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors to treat hemorrhages of all kinds.</span></p>','is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Pituitary Gland Tumors','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Raised Intracranial Pressure','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Spinal Cord Injuries','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Spinal Disc Herniation','description' => NULL,'is_active' => '1','parent_id' => '140','created_by' => NULL),
            array('name' => 'Raised Intracranial Pressure','description' => NULL,'is_active' => '0','parent_id' => '140','created_by' => NULL),
            array('name' => 'Clinical and interventional cardiology','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Endovascular intervention','description' => NULL,'is_active' => '1','parent_id' => '130','created_by' => NULL),
            array('name' => 'Piles','description' => '<h2 style="text-align: justify;"><strong>About Piles</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Piles also called hemorrhoids are inflamed and swollen tissues in the anal and rectal area.&nbsp;</span><span style="font-weight: 400;">Piles can develop inside the rectal area (internal hemorrhoids) or outside the skin around the anus (external hemorrhoids).&nbsp;</span><span style="font-weight: 400;">Hemorrhoids are a very common condition affecting three out of four adults once in their lifetime.</span></p>
          <h2 style="text-align: justify;"><strong>Symptoms</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Symptoms of hemorrhoids depend upon the type of hemorrhoid</span></p>
          <h2 style="text-align: justify;"><strong>External Hemorrhoids</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">In external hemorrhoids, a swollen outgrowth of tissues is around the anus. Symptoms include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Itching around the anal region</span></li>
          <li><span style="font-weight: 400;">Extreme pain and discomfort</span></li>
          <li><span style="font-weight: 400;">Swelling in the anal region</span></li>
          <li><span style="font-weight: 400;">Bleeding</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Internal Hemorrhoids</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Internal hemorrhoids occur inside the rectal area and it is not visible outside. Symptoms include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Painless bleeding while passing stool</span></li>
          <li><span style="font-weight: 400;">Protruding hemorrhoid results in pain and irritation</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Causes</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Exact causes of piles are still not clear, but this may occur due to</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Chronic constipation</span></li>
          <li><span style="font-weight: 400;">Chronic Diarrhea</span></li>
          <li><span style="font-weight: 400;">Pregnancy</span></li>
          <li><span style="font-weight: 400;">Increased pressure in the lower rectum</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Piles can resolve on its own without the need for extensive treatment. Some treatment option include</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Lifestyle changes including dietary change and weight loss</span></li>
          <li><span style="font-weight: 400;">Increased water consumption</span></li>
          <li><span style="font-weight: 400;">Medication and laxatives</span></li>
          <li><span style="font-weight: 400;">Surgical options&nbsp;</span></li>
          </ul>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '144','created_by' => NULL),
            array('name' => 'Otologist','description' => '<h2 style="text-align: justify;"><strong>About Otologist&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An otologist is a highly qualified doctor who specifically specializes in the ear, nose, and throat. They can also be called neurotologists though otologists and neurotologists are different in a few ways. Otologists focus on diagnosing difficult to treat or recurring middle ear problems while also being able to perform complex/complicated ear surgeries. Neurotologists on the other hand focus on more neurological-related problems and the inner ear like skull base tumors, hearing devices like cochlear implants and bone conduction hearing aids, and balance disorders. Both are able to treat ear disorders such as cholesteatoma and meniere&rsquo;s disease though. To become an otologist, a person is recommended to become a certified otolaryngologist first where they then have to complete a year of general surgery training and once that&rsquo;s done, four years of specific otolaryngology training. Otolaryngologists are special doctors who are trained to. Once certified in otolaryngology, they may pick otology as the specific field they want to enter into.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Otologists can treat complex ear diseases, hearing loss by implanting a hearing device, tumor in or around the ear, perform revision ear surgery, complicated vertigo that doesn&rsquo;t seem to be improving, and helping with recurring or chronic ear infections as an otolaryngologist. Specifically as an otologist, they can identify the signs and symptoms of Meniere&rsquo;s disease, finding the causes of tinnitus and finding ways to treat it, and they can also define the development and as to how otitis media progresses. Otolaryngologists can manage sinusitis, allergies, mouth and throat cancer, ear infections, dizziness, hearing loss, masses of the neck, trauma to the face and neck, and to help with other problems that are related to the structures of the head and neck.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t types of otologists but rather they&rsquo;re differentiated with how qualified and experienced they are.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best otologists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Tympanoplasty','description' => NULL,'is_active' => '0','parent_id' => '205','created_by' => NULL),
            array('name' => 'Nasal Procedures','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => NULL),
            array('name' => 'Orbital complications','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => NULL),
            array('name' => 'laryngoscopy','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => NULL),
            array('name' => 'Pharyngo','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => NULL),
            array('name' => 'Oesophagoscopy','description' => NULL,'is_active' => '1','parent_id' => '139','created_by' => NULL),
            array('name' => 'laryngectomy neck dissections','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => NULL),
            array('name' => 'Submandibular gland','description' => NULL,'is_active' => '1','parent_id' => '280','created_by' => NULL),
            array('name' => 'Thyroglossal cyst excisions','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => NULL),
            array('name' => 'Fiberoptic Endoscope','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => NULL),
            array('name' => 'Microlaryngoscopy','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => NULL),
            array('name' => 'Endoscopic Sinus Surgery','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => NULL),
            array('name' => 'Trauma surgery','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Lower back pain','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Fracture treatment','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Endoscopic percutaneous spine surgery','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Bone trauma','description' => NULL,'is_active' => '0','parent_id' => '1','created_by' => NULL),
            array('name' => 'Bone Health Care','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Chronic Wound, cerebral palsy and diabetic foot Management','description' => NULL,'is_active' => '1','parent_id' => '120','created_by' => NULL),
            array('name' => 'Height Increase By Limb Lengthening Surgery','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => NULL),
            array('name' => 'Joints Replacement Surgery','description' => NULL,'is_active' => '0','parent_id' => '62','created_by' => NULL),
            array('name' => 'Knee and Lower back pain treatments','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Orthopaedic Cosmetology','description' => NULL,'is_active' => '0','parent_id' => '62','created_by' => NULL),
            array('name' => 'Orthopaedic Physiotherapy','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Osteoporosis','description' => NULL,'is_active' => '0','parent_id' => '62','created_by' => NULL),
            array('name' => 'Scoliosis','description' => NULL,'is_active' => '1','parent_id' => '137','created_by' => NULL),
            array('name' => 'Vascularized Flaps','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => NULL),
            array('name' => 'Skin Diseases','description' => NULL,'is_active' => '0','parent_id' => '123','created_by' => NULL),
            array('name' => 'Acne & Melasma treatment','description' => '<h2 style="text-align: justify;"><strong>What is Acne &amp; Melasma Treatment?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Melasma is a relatively common skin problem which causes brown to gray-brown patches to appear mostly on the face. But it is also common for people to get it on their cheeks, bridge of their nose, chin, above their upper lip and forehead. It may potentially also appear in areas where the body gets a lot of sun such the neck and forearms. Causes of melasma can include skin care products that irritate the skin making it worse, a change in hormones, and sun exposure.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Treatment for Melasma is mostly the use of skin care products. After a dermatologist has a look at the severity of the Melasma, they will prescribe the skin care products needed to treat it. Another option of treatment, if the medicine applied doesn&rsquo;t get rid of it, is the use of laser, light-based procedure, microdermabrasion, chemical peel or dermabrasion.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best dermatologists available to treat Melasma.</span></p>','is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Hair & nail treatment','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Inj Botox for wrinkles','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Inj Collagen for laugh lines','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Laser Resurfacing','description' => NULL,'is_active' => '0','parent_id' => '123','created_by' => NULL),
            array('name' => 'Laser Peels','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Laser Skin Tightening & Face Lifts','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Skin allergy tests','description' => NULL,'is_active' => '0','parent_id' => '123','created_by' => NULL),
            array('name' => 'Microdermabrasion','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Photo-therapy for Vitiligo/Psoriasis/Eczema','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Cauterization for Warts','description' => NULL,'is_active' => '1','parent_id' => '123','created_by' => NULL),
            array('name' => 'Nutritionist','description' => '<h2 style="text-align: justify;"><strong>About Nutritionist&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A nutritionist is an expert individual who is qualified and certified to promote health and the managing of diseases by the right use of food and nutrition. Nutritionists mostly work in hospitals, long-term care facilities, medical offices, or nursing homes advising people on what they should eat in order for them to lead a healthy lifestyle or for them to be able to achieve a health-related goal. Nutritionists as a term isn&rsquo;t regulated, which means anyone can call themselves a nutritionist even without any formal training, certification, or license. This is one of the huge differences between a nutritionist and dietician. Dietitians are regulated and have to be certified and qualified while nutritionists do not, essentially meaning every dietitian is a nutritionist but not every nutritionist is a dietitian.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A nutritionist can provide clinical nutrition where they usually work in clinical settings, with each patient individually on inpatient and/or outpatient basis to assess, design and implement dietary strategies and nutritional therapies. This is usually for a particular medical issue that needs to be resolved like hypertension, obesity, or diabetes. They can at times be called to come up with a plan of action for those that have their overall diets affected by a treatment protocol like chemotherapy. A nutritionist can also work in places such as schools, locat, state, and federal government agency programs, community health clinics and recreational centers, and health maintenance organizations (HMOs) for children, at risk families, and the elderly designed to address certain nutritional issues.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are a few different types of nutritionists. Sports nutritionists help athletes enhance their athletic performances by assigning them the right diet and nutrients to do just that.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pediatric nutritionists aim to provide and promote the best nutritional health for infants, children, and adolescents.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">Gerontological nutritionists are specialists that design, manage, and put effective and nutrition strategies into action advocating quality of life and health for older adults. This is a growing sub speciality as there is a growth of awareness in how integral nutrition is to healthy ageing and disease management.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best nutritionists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><br /><br /><br /></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Acupuncturist','description' => '<h2 style="text-align: justify;"><strong>About Acupuncturist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An acupuncturist is an individual who is certified and qualified to practice acupuncture. Acupuncture is a form of &lsquo;alternative medicine&rsquo; and is not scientific based. It involves inserting a needle in certain points of the skin, usually penetrating it, to stimulate them to calm or get rid of pain or to treat different types of health conditions. To be a certified or qualified acupuncturist, it is generally agreed, in countries such as United States, Japan, United Kingdom, Australia, Canada, and more that if a physician is looking to become an acupuncturist, they should receive at least 200 hours of specialised training while non physicians should receive around 2,500 hours specialised training.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">While there is no scientific proof that acupuncture points exist, experts use neuroscience to explain acupuncture by stating the points are where nerves, muscles, and connective tissue(s) can be stimulated. This stimulation has been seen to increase blood flow also triggering the activity of the body&rsquo;s natural painkillers. Acupuncturists, by using acupuncture, have been proven to help lessen or fix low back pain, knee pain, headache, migraine, neck pain, and osteoarthritis. World Health Organization back in 2003 concluded that it has proven effective in a number of other conditions like high and low blood pressure, some gastric conditions, painful periods, facial pain, morning sickness, dysentery, sprain, dental pain, chemotherapy-induced nausea and vomiting, reducing the risk of stroke, sciatica, and among other conditions. It&rsquo;s also seen to help the following conditions, which require more investigation, such as stiff neck, spine pain, vascular dementia, tourettes/tourette syndrome, whooping cough, fibromyalgia, substance, tobacco, and/or alcohol dependence. Overall the benefits include there being very few side effects, can control some types of pain, can be very effective when combined with other treatments, aid patients who are not suited/suitable to take pain medication, and when it is performed correctly, it is safe.</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t types of acupuncturists; rather they&rsquo;re differentiated with how qualified and experienced they are.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best acupuncturists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Physiotherapist','description' => '<h2><strong>About Physiotherapist</strong></h2>
          <p><span style="font-weight: 400;">Physiotherapy is a science-based technique involving different treatments and preventive measures to cure any illness or injury. Physiotherapy aims to relieve pain to help a person in functioning better and to live better.</span></p>
          <ul>
          <li><span style="font-weight: 400;">Physical therapy helps in</span></li>
          <li><span style="font-weight: 400;">Relieving pain</span></li>
          <li><span style="font-weight: 400;">Improving movement and mobility</span></li>
          <li><span style="font-weight: 400;">Preventing disability</span></li>
          <li><span style="font-weight: 400;">Managing chronic illness like diabetes, heart diseases, etc</span></li>
          <li><span style="font-weight: 400;">Recovery after giving birth</span></li>
          </ul>
          <p><span style="font-weight: 400;">People of all ages can get physical therapy as it treats various health issues.</span></p>
          <h2><strong>What they can do</strong></h2>
          <p><span style="font-weight: 400;">Physiotherapist recommends exercise and movements to help in improving functioning and mobility of the body including</span></p>
          <ul>
          <li><span style="font-weight: 400;">Exercises that improve movement and strength of a specific body part.</span></li>
          <li><span style="font-weight: 400;">Activities that help in recovering from an operation or an injury</span></li>
          <li><span style="font-weight: 400;">Hydrotherapy</span></li>
          <li>Mobility aids</li>
          </ul>
          <p><span style="font-weight: 400;">The physiotherapist may also recommend various exercises to help manage pain in the long term.&nbsp;</span></p>
          <p><span style="font-weight: 400;">There are three main approaches in physiotherapy</span></p>
          <ul>
          <li><span style="font-weight: 400;">Education and advice</span></li>
          <li><span style="font-weight: 400;">Movement and exercise</span></li>
          <li><span style="font-weight: 400;">Manual therapy</span></li>
          </ul>
          <h2><strong>Appointment booking</strong></h2>
          <p><span style="font-weight: 400;">Before getting started with physiotherapy treatments, search the best physiotherapist for yourself. You can book an instant appointment with the best physiotherapist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you. </span></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Allergy Specialist','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Cardiac Surgeon','description' => '<h2 style="text-align: justify;"><strong>About Cardiac Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is a medical specialty that makes use of manual machinery and instrumental techniques on a person to either find out or treat a disease or injury, to help better the body&rsquo;s function, appearance, or to repair unwanted ruptured areas. A cardiothoracic surgeon focuses on the organs inside the chest, mainly the heart and lungs, though in most countries the heart and lungs have separate specialties. A cardiac surgeon is one that only performs heart related procedures treating heart diseases. Surgeons must first obtain a bachelor&rsquo;s degree from an accredited university and while there&rsquo;s not a specific major that&rsquo;s needed to become a surgeon, most people will choose to study science, others may choose to study biology, health science, chemistry, kinesiology, and physics. While in university, they need to fulfill pre-med course requirements, gain experience, and complete their MCAT to get into medical school. Once finished with med school, the student will have to complete surgical residency which will take five years. An additional two years-three years is required to become a cardiac surgeon.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A cardiac surgeon is able to perform heart surgery techniques like open-heart surgery, modern beating-heart surgery, and more. With open heart surgery, the patient&rsquo;s heart is opened while the surgeon performs surgery on the internal structures of it. This technique is used to repair or replace heart valves, repair damaged or abnormal areas of the heart, insert medical devices that help the heart-beat the right way, and for heart transplantation. Modern beating-heart surgery or off-pump coronary artery bypass surgery is coronary artery bypass surgery without a cardiopulmonary bypass. The heart is beating during the procedure while being stabilized so that the surgeon can work in an almost still work area.&nbsp;&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of Cardiac Surgeons but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best Cardiac Surgeons in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Maxillo Facial Surgeon','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Neuro Surgeon','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Pathologist','description' => '<h2 style="text-align: justify;"><strong>About Pathologists</strong></h2>
          <p style="text-align: justify;">A physician who studies body fluids and tissues is pathologist. He helps your regular doctor to make a diagnosis about your health or any medical conditions you have, and uses laboratory tests to examine the health of patients with chronic symptoms.</p>
          <p style="text-align: justify;">Pathologists care for patients every day by giving their doctors with the information needed to ensure suitable patient care. They are important resources for other physicians 24 hours a day.</p>
          <h2 style="text-align: justify;"><strong>What they do</strong></h2>
          <ul style="text-align: justify;">
          <li>A pathologist will also look at a tissue biopsy to check whether it is benign or you have cancer, and shares that information with your regular doctor.</li>
          <li>They may also suggest steps you can take to avoid illness and maintain good health. For example, when your blood is drawn as part of your yearly physical checkup, a pathologist may direct testing or perform tests to help evaluate your health.</li>
          <li>Some pathologists specialize in genetic testing, which can, for example, determine the most suitable treatment for particular types of cancer.</li>
          <li>Pathologists also carry out autopsies, which not only determine the person&rsquo;s cause of death, but may also find out more information about the genetic succession of a disease. This examination can help family members take precautionary action for their own health and can aid researchers in developing future treatments.</li>
          </ul>
          <h2 style="text-align: justify;"><strong>Appointment booking</strong></h2>
          <p style="text-align: justify;">Before getting started with pathology treatment, search the best pathologist in your area. You can book an instant appointment with the best pathologist in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Pain Specialist','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Medical Specialist','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Cosmetologist','description' => '<h2 style="text-align: justify;"><strong>About Cosmetologist&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A cosmetologist is a part of cosmetology, which is a big section of the beauty industry. It can be confusing as to what they actually do because there are many specialties within it but, cosmetologists are mostly focused on cosmetic treatments aimed at the hair, skin, and nails. They can expand into other parts if they gain the knowledge to do so. To become a cosmetologist, the individual needs to go to a recognised cosmetology school, do a certified cosmetology course, or go through a few courses to acquire the necessary skills.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cosmetologists not only perform cosmetic treatment on hair, skin, and nails, but they can expand into multiple parts where they&rsquo;re able to cut and chemically treat hair, perform chemical hair removal, put wigs on their customers, and help with the latest fashion trends. Additionally, cosmetologists can also perform skin and hair analysis along with relaxation techniques on the head, neck, scalp, hand, and feet. They may be able to expertly apply makeup to cover and can expand into specialties like reflexology. A more qualified cosmetologist can be a hair color specialist, shampoo technician, and/or an aesthetician. A hair color specialist specializes in changing the natural hair color of their customer, to whatever color dye they want, with maximum effect and efficiency with the least amount of damage. A shampoo technician conditions and prepares the client&rsquo;s hair for the hair stylist. An aesthetician is a professional who is skilled in maintaining and improving one&rsquo;s skin and you can find them anywhere like in a spa, skin care clinic, and private practices.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of cosmetologists but they can be differentiated with the amount of experience they have and what additional things they are able to do.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best General Physicians in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><br /><br /><br /></p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Pain Physician','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Sports Physician','description' => '<h2 style="text-align: justify;"><strong>About Sports Physician&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A sports physician is a doctor who has specialist training in managing musculoskeletal injuries and diseases in order for their patient to function at the max, reduce any chance of disability, and to minimize time away from the patient&rsquo;s school, place of work, and/or sport. They are more suited to give extensive medical care to athletes, sports teams, or even simply active patients who want to maintain a healthy lifestyle. Sports physicians who only provide non-surgical sports medicine are called sports medicine physicians and they are team physicians at youth level, in professional sports teams like in the NFL, NBA, MLB, all professional football (soccer) teams in every country, and even olympic teams. To become a sports physician, doctors have to have a minimum of 7 years training after medical school to become a certified sports physician.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Sports physicians perform non-operative treatment on their patients but 90% of musculoskeletal injuries are non-surgical. Physicians can help maximize and create the next non-operative treatment and guide their patients to the right rehab programs and therapies and if needed, refer to their surgical colleagues. Physicians take more training to treat problems like concussions and other head injuries, use the right tools including use of ultrasound, treating athletes who have acute or chronic diseases such as asthma or diabetes, recommend the best nutrition, supplements, and anything else for performance optimization, assign the right workout routine/exercises so that the patient can increase their fitness and help with preventative health, prevent injury, advise whether it is okay to return to playing sport, and they also promote a healthy lifestyle.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t types of sports physicians but rather they&rsquo;re differentiated with how qualified and experienced they are.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best sport physicians in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Stem Cell','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Prolotherapy','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'RFA (Radio frequency ablation)','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Hepatologist','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Spine Surgery','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'diabetic nephropathy (D&N)','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'TVS ( Transvaginal) Ultrasound','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Abdominal Ultrasound','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => NULL),
            array('name' => 'Intracytoplasmic Sperm Injection (ICSI)','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Embryo Freezing','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Sperm Freezing','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Intrauterine Insemination (IUI)','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Testicular Sperm Aspiration (TESA) ','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Semen Analysis','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Semen Analysis','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Dental Surgery','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Dental Surgery','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Oral and Maxillofacial Surgeons','description' => '<h2 style="text-align: justify;"><strong>About Oral and Maxillofacial Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is a medical specialty that makes use of manual machinery and instrumental techniques on a person to either find out or treat a disease or injury, to help better the body&rsquo;s function, appearance, or to repair unwanted ruptured areas. Oral and maxillofacial surgery is one of many surgery specialties. An oral and maxillofacial surgeon focuses on reconstructive face surgery, facial trauma surgery, head and neck, mouth and jaws, the oral cavity, and they are also able to perform facial cosmetic surgery. Surgeons must first obtain a bachelor&rsquo;s degree from an accredited university and while there&rsquo;s not a specific major that&rsquo;s needed to become a surgeon, most people will choose to study science, others may choose to study biology, health science, chemistry, kinesiology, and physics. While in university, they need to fulfill pre-med course requirements, gain experience, and complete their MCAT to get into medical school. Once finished with med school, the student will have to complete surgical residency which will take six years and grant them their degree. A person can also become an oral and maxillofacial surgeon by going through dental school where after dental school, they will require four to six years of additional university training.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An oral and maxillofacial can do cosmetic surgery of the head and neck with some procedures including rhinoplasty, lip enhancement, botox, stem cells, and many more. They can perform orthognathic surgery, and teeth related procedures dentoalveolar surgery removing impacted teeth, difficult tooth extractions, and they may also insert bone fused dental implants.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of oral and maxillofacial surgeons but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best in your area on the oral and maxillofacial surgeons HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>
          <p style="text-align: justify;"><br /><br /><br /></p>','is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Pediatric Surgeon','description' => '<h2 style="text-align: justify;"><strong>About Pediatric Surgeon</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Surgery is a medical specialty that makes use of manual machinery and instrumental techniques on a person to either find out or treat a disease or injury, to help better the body&rsquo;s function, appearance, or to repair unwanted ruptured areas. Pediatric surgeons are a part of a surgical speciality who specifically perform surgery on fetuses, infants, children, adolescents, and young adults. Surgeons must first obtain a bachelor&rsquo;s degree from an accredited university and while there&rsquo;s not a specific major that&rsquo;s needed to become a surgeon, most people will choose to study science, others may choose to study biology, health science, chemistry, kinesiology, and physics. While in university, they need to fulfill pre-med course requirements, gain experience, and complete their MCAT to get into medical school. Once finished with med school, the student will have to complete surgical residency which will take five years. An additional year-three years may be required to learn subspecialties.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Pediatric surgeons are able to treat many types of children related diseases. Common pediatric diseases that require surgery include childhood tumors, abdominal wall defects, congenital malformations, chest wall deformities, and the separation of conjoined twins. Some congenital malformations include cleft lip and palate, intestinal malrotation, intestinal asteria. Abdominal wall defects that can be fixed by surgery are hernias, omphalocele, and gastroschisis. Childhood tumors that are treated by pediatric surgeons are neuroblastoma, liver tumors, Wilms&rsquo; tumor and more.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t different types of General Surgeons but they can be differentiated with the amount of experience and additional skills they have.</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best Pediatric Surgeon in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Thoracic Surgery','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'OPD','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Indoor Pharmacy','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Histopathology Lab','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Myomectomy','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Hysterotomy','description' => '<h2 style="text-align: justify;"><strong>What is a hysterectomy?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The surgical procedure to remove a woman&rsquo;s uterus, which is also known as their womb, is called a hysterectomy. Once this is done, the woman will not have any more menstrual periods and will not be able to get pregnant. The reason for this procedure may be because of chronic pain conditions and because of cancer and infections like cancer in the uterus, chronic pelvic pain, serious infection on the reproductive organs called pelvic inflammatory disease, and even because of profuse vaginal bleeding. Usually, the whole of the uterus is taken out and the ovaries and the fallopian may also be removed too depending on the situation.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are different types of hysterectomy. A partial hysterectomy removes a part of the uterus, total hysterectomy removes the whole of the uterus along with the cervix, and hysterectomy and salpingo-oophorectomy remove the uterus with addition to one or both of the ovaries and fallopian tubes. The procedure can be done in multiple ways but in all of the types. The patient is given either general or local anesthetic to relax them during the surgery. The uterus can either be removed with an abdominal hysterectomy creating a large cut in the abdomen, a vaginal hysterectomy where the uterus is removed through a small cut in the vagina, or with a laparoscopic hysterectomy using the laparoscopic approach to take out the uterus.&nbsp;</span></p>','is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Ovarian Cyst Surgery','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Oophorectomy','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Salpingectomy for Ectopic Pregnancy','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Tubal Ligation','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Laparoscopic Surgeon','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'Breast Biopsy','description' => '<h2 style="text-align: justify;"><strong>What is Breast Biopsy?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Breast biopsies are a procedure done to remove a small sample of breast tissue for testing within a laboratory. This test is used to analyse a suspicious area and to usually see whether or not it is breast cancer. There are many different ways to perform a breast biopsy, these include fine-needle aspiration biopsy, core needle biopsy, stereotactic biopsy, MRI-guided core needle biopsy, ultrasound-guided core needle biopsy, and surgical biopsy. Risks of all biopsies include swelling, bruising and an altered breast appearance.&nbsp;&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The doctor will decide what biopsy treatment is the best depending on the situation. Once the procedure has been done, the breast tissue is sent to the lab to be analysed by a doctor who specialises in analysing blood and body tissue, using a microscope and special procedures. Results may take up to several days and will be presented with full information including size and consistency of the tissue samples and whether or not cancer, noncancerous changes or precancerous cells were present. After a breast biopsy, the patient will go home with bandages and an ice pack on the biopsy site where they should rest for the day, being able to resume normal activity the next day. If it was a surgical biopsy, there will be stitches to care for but will go home the same day and can also resume activity the next day. Depending on the results, further treatment or surgery may be required.&nbsp;</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best doctors for a breast biopsy.</span></p>','is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Breast Lumpectomy','description' => NULL,'is_active' => '0','parent_id' => '436','created_by' => '1'),
            array('name' => 'Laparotomy','description' => '<h2><strong>What is Laparotomy?</strong></h2>
          <p><span style="font-weight: 400;">Laparotomy is a surgical procedure in which incision is made into an abdominal cavity. The incision is made to examine the abdominal organs and to diagnose any problem including abdominal pain. Laparotomy is also called abdominal exploration.</span></p>
          <p><span style="font-weight: 400;">A common reason for Laparotomy is to investigate the reasons for abdominal pain. The abdominal organs include digestive system organs (Stomach, intestines and liver) and other organs like kidneys and bladder.</span></p>
          <h2><strong>Before the operation</strong></h2>
          <p><span style="font-weight: 400;">Certain protocols are followed before the operation</span></p>
          <ul>
          <li><span style="font-weight: 400;">You are shaved in the abdominal area</span></li>
          <li><span style="font-weight: 400;">You are given a surgical scrub lotion to use in shower and gown to wear.</span></li>
          <li><span style="font-weight: 400;">You are given an enema to help you empty your bowels.</span></li>
          <li><span style="font-weight: 400;">You&rsquo;ll have nothing to eat hours before the procedure.</span></li>
          </ul>
          <h2><strong>Laparotomy procedure</strong></h2>
          <p><span style="font-weight: 400;">It is performed under general anesthesia. A single cut is made through the skin and muscle of the abdomen so underlying organs are viewed clearly.&nbsp; Once the problem is diagnosed, it may be foxed at the same time or in some cases, a second operation is needed. Once the procedure is complete, the overlying skin is sewn closed.&nbsp;</span></p>
          <p><span style="font-weight: 400;">You can consult the best laparoscopic surgeon through the HospitALL website and app.&nbsp;</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Hemorrhoids Surgery','description' => NULL,'is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Laparoscopic Cholecystectomy','description' => NULL,'is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Inguinal Hernia','description' => '<h2 style="text-align: justify;"><strong>What is Inguinal Hernia?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An inguinal hernia is a hernia that appears when a tissue penetrates into the abdominal muscles through a weak spot creating a bulge. This may cause pain on that bulge which is felt when coughing, when bending over, or while lifting a heavy object. The hernia isn&rsquo;t dangerous but it doesn&rsquo;t improve on its own and can lead to life-threatening problems. It can be caused by pregnancy, constant coughing/sneezing, highly straining activity, increased pressure inside the abdomen, and more reasons.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Usually when any hernia is small, the doctor prefers to wait and see if it grows in size and becomes worse. Once this does happen, the only way to fix it is by performing surgery and there are two ways to do this. Either by an open hernia repair or laparoscopy. While laparoscopy is minimally invasive and has a lesser recovery time, a recurrence of the hernia is more likely than it is in an open hernia surgery so that may be a better option but that also means several weeks before the patient resumes normal activity.&nbsp;</span><span style="font-weight: 400;">HospitALL provides the best doctors and surgeons to treat inguinal hernias.</span></p>','is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Thyroid Surgery','description' => NULL,'is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Colon Surgery','description' => NULL,'is_active' => '1','parent_id' => '436','created_by' => '1'),
            array('name' => 'Wrist Surgery for Carpal Tunnel','description' => '<h2><strong>What is Surgery for Carpal Tunnel?</strong></h2>
          <p><span style="font-weight: 400;">Carpal tunnel syndrome is caused when pressure is formed on the median nerve. The Carpal tunnel is a narrow path made of bones and ligament. The median nerve passes through the narrow path.&nbsp;</span><span style="font-weight: 400;">Due to swelling in the wrist, the narrow tunnel gets squeezed thus affecting the median nerve which further causes the symptoms.</span></p>
          <p><strong>Types of surgeries</strong></p>
          <p><span style="font-weight: 400;">There are two types of carpal tunnel surgeries.</span></p>
          <ul>
          <li><strong>Open surgery</strong><span style="font-weight: 400;">&nbsp;involves a larger incision ranging up to 2cm from wrist to palm.</span></li>
          <li><strong>Endoscopic surgery</strong><span style="font-weight: 400;"> involves one cut in the wrist and another in the arm. The cuts are smaller by about half an inch. A camera is inserted which guides the surgeon to cut the ligament.</span></li>
          </ul>
          <p><span style="font-weight: 400;">As the cuts are smaller, endoscopic surgery heals faster.&nbsp;</span><span style="font-weight: 400;">Your doctor can recommend the best option for you.&nbsp;</span></p>','is_active' => '1','parent_id' => '144','created_by' => '1'),
            array('name' => 'Fracture Neck of Femur','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => '1'),
            array('name' => 'Laminectomy for Spinal Disc','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => '1'),
            array('name' => 'Arthroscopic Procedure','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => '1'),
            array('name' => 'Open Reduction Internal Fixation of Fracture','description' => NULL,'is_active' => '1','parent_id' => '1','created_by' => '1'),
            array('name' => 'Nose Job','description' => NULL,'is_active' => '0','parent_id' => '403','created_by' => '1'),
            array('name' => 'tummy tuck','description' => NULL,'is_active' => '0','parent_id' => '403','created_by' => '1'),
            array('name' => 'Breast Reduction','description' => NULL,'is_active' => '0','parent_id' => '403','created_by' => '1'),
            array('name' => 'Blepharoplasties','description' => '<h2 style="text-align: justify;"><strong>What is Blepharoplasties?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Blepharophimosis is an eye condition present from birth which mainly affects the development of the eyelids. Those with the condition have droopy eyelids, a narrowing eye opening, an upward fold of the skin on the lower eyelid near the inner corner of the eye, and an increased distance between the inner corners of the eyes. Due to these defects on the eyelid, the eyelids cannot be opened fully which can make the vision limited.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The only way to treat this condition is to have surgery and traditionally, surgery is done for patients at ages 3-5 with another surgery a year later to complete the repair.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best eye specialists and eye surgeons available.</span></p>','is_active' => '1','parent_id' => '403','created_by' => '1'),
            array('name' => 'Lip Augmentation','description' => NULL,'is_active' => '1','parent_id' => '403','created_by' => '1'),
            array('name' => 'Gynaecomastia','description' => NULL,'is_active' => '1','parent_id' => '403','created_by' => '1'),
            array('name' => 'Testicular Sperm Aspiration (TESA)','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => '1'),
            array('name' => 'Intrauterine Insemination (IUI)','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => '1'),
            array('name' => 'Sperm Freezing','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => '1'),
            array('name' => 'Embryo Freezing','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => '1'),
            array('name' => 'ICSI (intracytoplasmic sperm injection)','description' => NULL,'is_active' => '1','parent_id' => '33','created_by' => '1'),
            array('name' => 'Spine Surgery','description' => NULL,'is_active' => '1','parent_id' => '62','created_by' => '1'),
            array('name' => 'Abdominal Ultrasound','description' => '<h2 style="text-align: justify;"><strong>What is an Abdominal Ultrasound?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An abdominal ultrasound is when the ultrasound technology is used to get imaging of the structures with the upper abdomen. This is used to help diagnose pain or enlargement of the kidneys, gallbladder, pancreas, abdominal aorta, bile ducts, and liver.</span></p>
          <h2 style="text-align: justify;"><strong>More on Ultrasound</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">An ultrasound is the use of sound waves to produce pictures of inside of the body. It doesn&rsquo;t need any special preparation but the doctor may say not to drink or eat before it. Must wear comfortable clothes without any jewelry. The doctors will most likely ask you to wear a gown.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best clinics and doctors for an ultrasound.</span></p>','is_active' => '1','parent_id' => '33','created_by' => '1'),
            array('name' => 'TVS Ultrasound','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'D&C (Dilation and curettage)','description' => NULL,'is_active' => '1','parent_id' => '143','created_by' => '1'),
            array('name' => 'Orthopedic Surgery','description' => NULL,'is_active' => '0','parent_id' => '62','created_by' => '17'),
            array('name' => 'Sexologist','description' => '<h2 style="text-align: justify;"><strong>About Sexologist</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A sexologist is a doctor who specialises in sexology. Sexology itself is the study of human sexuality which includes a human&rsquo;s sexual interests, their behaviors, and functions. The more specifically focused areas, but not limited to, are human reproduction, consent and sexual consent, gender diversity and sex, sexual diversity, and the population&rsquo;s health response to sexual health challenges. In recent times, the study of love, sexual emotions, human relationships, human sexual response, sexual function, sexual pleasure and fulfilment have been included in sexology. There are university courses to become a sexologist; it isn&rsquo;t regulated and you don&rsquo;t need to be qualified to become one. The degree is four years long but a sexologist can have an educational background in sociology, biology, or/and public health, and more to be one.</span></p>
          <h2 style="text-align: justify;"><strong>What They Can Do</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">A sexologist can use their skills in many ways but they&rsquo;re more commonly seen as sex therapists who have clients that either see them on an individual or couple basis to improve their sex lives. This ranges from having troubles gaining pleasure to not having the same sex drive as your partner to having a sexless relationship with your partner. Visiting a sexologist is like visiting a therapist, they hear your problems, whether that be low confidence, erectile dysfunction, low sex drive, high sex drive, not being able to control yourself, and more, helping their patient work through it by giving them the right advice and coping mechanisms. The work they do improves their clients sex life by making it as healthy as possible for them. It may take a few sessions before the client(s) get to where they want to be. If there are symptoms of dysfunction, the sexologist may refer their patient to a medical doctor to solve them.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Types</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">There aren&rsquo;t types of sexologists; rather they&rsquo;re differentiated with how qualified and experienced they are.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Appointment Booking&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">You can book an instant appointment with the best acupuncturists in your area on the HospitALL website. You can also call our CareALL helpline. Our dedicated CareALL staff is always there to help you.&nbsp;</span></p>','is_active' => '1','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'CyberKnife','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Deep Brain Stimulation (DBS)','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Gamma Knife','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Minimally Invasive Spine Surgery','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Nerve Decompression','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Nerve Reconstruction Surgery','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Anatomy of the Brain','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Neurological Diagnostic Tests','description' => NULL,'is_active' => '0','parent_id' => '397','created_by' => '1'),
            array('name' => 'Stem Cell Research','description' => NULL,'is_active' => '1','parent_id' => '397','created_by' => '1'),
            array('name' => 'Cervical Spine Interventions','description' => NULL,'is_active' => '1','parent_id' => '400','created_by' => '1'),
            array('name' => 'Lumbar Sacral Spine Interventions','description' => NULL,'is_active' => '1','parent_id' => '400','created_by' => '1'),
            array('name' => 'Interventional Headache, Head/Facial Interventions','description' => NULL,'is_active' => '1','parent_id' => '400','created_by' => '1'),
            array('name' => 'Peripheral Nerve Blocks','description' => NULL,'is_active' => '1','parent_id' => '400','created_by' => '1'),
            array('name' => 'Peripheral Joints','description' => NULL,'is_active' => '1','parent_id' => '400','created_by' => '1'),
            array('name' => 'Cataract','description' => '<h2 style="text-align: justify;"><strong>What is a cataract?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Cataract is a condition that affects the eyes. It is the clouding of otherwise a normally clear eye lens. People with a cataract see things as if a person with normal vision is seeing them through a frosty or foggy window. It is harder for them to read, drive a car or see the expressions of others. In most cases, cataracts take their time to properly disturb the visions of people and when this happens, surgery is usually required to deal with the cataract. The development of a cataract is usually down to the growth in age or injury changing the tissues with the eye&rsquo;s lens. Other reasons may be genetic disorders, other eye conditions, past eye surgery or diabetes, and the long-term use of steroids. There are different types of cataracts and they include, cataracts affecting the center, the edge and the back of the lens and cataracts that are there from birth or childhood. The one that affects the back of the lens progresses the fastest.</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">The main and most common treatment for a cataract is surgery. Doctors will advise to do the surgery only if and when they start affecting the quality of life or the ability to perform normal tasks like reading or driving at night. There is usually no rush to do the procedure unless vision gets rapidly worse which tends to be the case with diabetes patients. Within the surgery, the clouded lens is removed and replaced with an artificial clear lens, placed where the natural lens would usually be remaining as a permanent of the eye. People with other eye problems may not be able to have an artificial lens, instead, once the cataract is removed, they wear eyeglasses or contact lenses. While the procedure is safe, it carries a risk of infection and bleeding and also increases the likelihood of retinal detachment. After the procedure, there will be discomfort for a few days while full healing happens within 8 weeks. In the case surgery for both eyes is needed, the doctor will operate one eye and then operate the other eye once the first eye has fully healed.</span></p>','is_active' => '1','parent_id' => '147','created_by' => '1'),
            array('name' => 'Diabetic Eye','description' => NULL,'is_active' => '1','parent_id' => '147','created_by' => '1'),
            array('name' => 'Pediatric Examination','description' => NULL,'is_active' => '1','parent_id' => '147','created_by' => '1'),
            array('name' => 'Refraction on Digital Phoropter','description' => NULL,'is_active' => '1','parent_id' => '147','created_by' => '1'),
            array('name' => 'Live Imagery of Examination','description' => NULL,'is_active' => '1','parent_id' => '147','created_by' => '1'),
            array('name' => 'Bonding','description' => '<h2 style="text-align: justify;"><strong>What is Bonding?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Bonding is a cosmetic dental procedure used to restore or improve a person&rsquo;s smile. It&rsquo;s used to repair chipped/cracked teeth, to change the shape of teeth, to close spaces between teeth, to repair decayed teeth, and more. While dental bonding has its advantages like having the least amount of tooth enamel removed, it has major disadvantages. It does not resist stains very well, the bonding materials do not last very long, it&rsquo;s not as strong as other restorative procedures and the materials can possibly chip and break off the tooth. Hence why dental bonding is best for small cosmetic changes and temporary corrections.&nbsp;</span></p>
          <h2 style="text-align: justify;"><strong>Treatment&nbsp;</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">In dental bonding, a durable plastic material, a tooth-colored resin material, is applied and hardened with a special light which bonds the material with the tooth. The procedure takes approximately 30-60 minutes per tooth. The bonding done on the tooth/teeth lasts up to 3-10 years before needing to be touched up or replaced providing oral hygiene is maintained like brushing teeth twice a day, flossing at least once a day, rinsing with an antiseptic mouthwash once or twice a day and regularly seeing the dentist for check ups and cleaning.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best dental care available</span></p>
          <p style="text-align: justify;"><br /><br /></p>','is_active' => '1','parent_id' => '4','created_by' => '1'),
            array('name' => 'Tonsillectomy','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Neck Mass Biopsy','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Parotidectomy','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Septoplasty','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Septoplasty','description' => NULL,'is_active' => '0','parent_id' => '99','created_by' => '1'),
            array('name' => 'Nasal valve reconstruction','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Myringoplasty','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Tympanoplasty','description' => NULL,'is_active' => '1','parent_id' => '99','created_by' => '1'),
            array('name' => 'Sleeve Gastrectomy','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => '1'),
            array('name' => 'Adjustable Gastric Band','description' => '<h2 style="text-align: justify;"><strong>What is an Adjustable Gastric Band?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Obesity is a disease where the body has an excessive amount of body fat. It is a real medical problem that increases the risk of other diseases and health problems such as diabetes, high blood pressure, certain cancers and heart disease. Adjustable Gastric Banding is a surgical solution for obesity, the procedure is known as a bariatric surgery. People who are obese can have a silcone band around the upper part of their stomach to decrease stomach size and reduce food intake as this decreases their appetite resulting in weight loss. This surgery is recommended for people of body mass index (BMI) 35 and above but may also be used on those with a BMI of 30-35 if they have obesity-related complications and non-surgical approaches do not work (dietary changes, medications, physical activity).</span></p>
          <h2 style="text-align: justify;"><strong>Treatment</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">After the procedure is done, for the first few days, the diet is restricted to water and fluids like thin soups. Then for 4 weeks, liquids and blended foods such as yogurt. For 4 to 6 weeks, soft foods can be eaten. After 6 weeks, a normal diet is restored.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">HospitALL provides the best surgeons for Adjustable Gastric Banding surgery.</span></p>
          <p>&nbsp;</p>','is_active' => '1','parent_id' => '32','created_by' => '1'),
            array('name' => 'Biliopancreatic Diversion with Duodenal Switch (BPD/DS)','description' => NULL,'is_active' => '0','parent_id' => '32','created_by' => '1'),
            array('name' => 'Phonation','description' => NULL,'is_active' => '1','parent_id' => '398','created_by' => '1'),
            array('name' => 'Resonance','description' => NULL,'is_active' => '1','parent_id' => '398','created_by' => '1'),
            array('name' => 'Fluency','description' => NULL,'is_active' => '1','parent_id' => '398','created_by' => '1'),
            array('name' => 'Intonation','description' => NULL,'is_active' => '1','parent_id' => '398','created_by' => '1'),
            array('name' => 'Variance of pitch','description' => NULL,'is_active' => '1','parent_id' => '398','created_by' => '1'),
            array('name' => 'Voice and respiration','description' => NULL,'is_active' => '1','parent_id' => '398','created_by' => '1'),
            array('name' => 'Bladder Catheterization','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Giving Immunizations','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Incision and ​​​Drainage of Abscess','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Lumbar Puncture (Cerebrospinal Fluid Collection)','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Simple Laceration Repair','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Simple Removal of a Foreign Body','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Temporary Splinting of a Fracture','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Reduction of Simple Dislocation','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Neonatal Endotracheal Intubation','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Bag-Mask Ventilation','description' => NULL,'is_active' => '1','parent_id' => '114','created_by' => '1'),
            array('name' => 'Venipuncture','description' => '<h2 style="text-align: justify;"><strong>What is Venipuncture?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Venipuncture is the procedure through which blood is collected from the veins usually done for laboratory testing or clinical purpose.</span></p>
          <p style="text-align: justify;"><span style="font-weight: 400;">There are some risks associated with Venipuncture</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">Excessive bleeding</span></li>
          <li><span style="font-weight: 400;">Fainting</span></li>
          <li><span style="font-weight: 400;">Nerve damage</span></li>
          <li><span style="font-weight: 400;">Hematoma formation</span></li>
          <li><span style="font-weight: 400;">Infection</span></li>
          </ul>
          <h2 style="text-align: justify;"><strong>How this is performed?</strong></h2>
          <p style="text-align: justify;"><span style="font-weight: 400;">Blood is usually collected from the vein located at the back of the hand or inside of the elbow.</span></p>
          <ul style="text-align: justify;">
          <li><span style="font-weight: 400;">The site where blood is to be withdrawn is cleaned with an antiseptic.</span></li>
          <li><span style="font-weight: 400;">A band is put around the area of upper arm and pressure is applied to make the vein swell.</span></li>
          <li><span style="font-weight: 400;">A needle is then inserted in the vein and blood is collected into an airtight tube attached to the needle.</span></li>
          <li><span style="font-weight: 400;">The band is removed and pressure is released.</span></li>
          <li><span style="font-weight: 400;">The needle is taken out and the area is covered with band-aid to prevent bleeding.</span></li>
          </ul>
          <p style="text-align: justify;">&nbsp;</p>','is_active' => '1','parent_id' => '144','created_by' => '1'),
            array('name' => 'Range of Motion Exercise','description' => NULL,'is_active' => '1','parent_id' => '393','created_by' => '1'),
            array('name' => 'Soft Tissue Mobilization','description' => NULL,'is_active' => '1','parent_id' => '393','created_by' => '1'),
            array('name' => 'Electrotherapy','description' => NULL,'is_active' => '1','parent_id' => '393','created_by' => '1'),
            array('name' => 'Cryotherapy and Heat Therapy','description' => NULL,'is_active' => '1','parent_id' => '393','created_by' => '1'),
            array('name' => 'Kinesio Taping','description' => NULL,'is_active' => '1','parent_id' => '393','created_by' => '1'),
            array('name' => 'Therapeutic Ultrasound','description' => NULL,'is_active' => '1','parent_id' => '393','created_by' => '1'),
            array('name' => 'Endocrinology & Diabetes','description' => NULL,'is_active' => '0','parent_id' => NULL,'created_by' => '1'),
            array('name' => 'EPIDURAL STEROID INJECTIONS','description' => NULL,'is_active' => '1','parent_id' => '404','created_by' => '1'),
            array('name' => 'KYPHOPLASTY','description' => NULL,'is_active' => '1','parent_id' => '404','created_by' => '1'),
            array('name' => 'NERVE BLOCKS','description' => NULL,'is_active' => '1','parent_id' => '404','created_by' => '1'),
            array('name' => 'RADIOFREQUENCY ABLATION (RFA)','description' => NULL,'is_active' => '1','parent_id' => '404','created_by' => '1'),
            array('name' => 'Needle insertion','description' => '<p>Acupuncture needles are inserted to various depths at strategic points on your body. The needles are very thin, so insertion usually causes little discomfort. People often don\'t feel them inserted at all. Between five and 20 needles are used in a typical treatment. You may feel a mild aching sensation when a needle reaches the correct depth.</p>','is_active' => '1','parent_id' => '392','created_by' => '1'),
            array('name' => 'Needle manipulation','description' => '<p>Your practitioner may gently move or twirl the needles after placement or apply heat or mild electrical pulses to the needles.</p>','is_active' => '1','parent_id' => '392','created_by' => '1'),
            array('name' => 'Needle removal','description' => '<p>In most cases, the needles remain in place for 10 to 20 minutes while you lie still and relax. There is usually no discomfort when the needles are removed.</p>','is_active' => '1','parent_id' => '392','created_by' => '1'),
            array('name' => 'XRAY','description' => '<h2><strong>What is X-Ray?</strong></h2>
          <p><span style="font-weight: 400;">X-ray is a common imaging procedure that is used to view the inside of the body without having to create an incision.&nbsp;</span><span style="font-weight: 400;">This is used for diagnosing, monitoring and treating various medical conditions.</span></p>
          <h2><strong>Why is X-ray performed?</strong></h2>
          <ul>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">Usually X-ray is performed for the following conditions</span></li>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">To examine the area of pain</span></li>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">To monitor the progression of the disease</span></li>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">To check the efficacy of the treatment</span></li>
          </ul>
          <h2><strong>Preparing for X-ray</strong></h2>
          <p><span style="font-weight: 400;">There are no special steps to prepare for the X-ray. Wear loose comfortable clothing.</span></p>
          <p><span style="font-weight: 400;">Sometimes you are required to take a contrast dye before undergoing an X-Ray. This contrast dye improves the quality of images. The dye may contain iodine or barium compounds. Contrast dye is given through different routes</span></p>
          <ul>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">By liquid intake</span></li>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">It can be injected in your body</span></li>
          <li><span style="font-weight: 400;"> &nbsp; </span> <span style="font-weight: 400;">Given as enema before the test</span></li>
          </ul>
          <p><span style="font-weight: 400;">If you are undergoing an X-ray to get your gastrointestinal region examined, you&rsquo;ll be required to do fast for a certain amount of time beforehand. You may also be restricted to drink certain liquids.</span></p>
          <h2><strong>How is X-ray performed?</strong></h2>
          <p><span style="font-weight: 400;">A radiologist or X-ray technologist can perform an X-ray.&nbsp;</span><span style="font-weight: 400;">You&rsquo;ll be asked to lie, sit or stand in several positions depending upon the area which is being examined. You&rsquo;ll be required to stand in front of a specialized plate containing X-ray film or sensors. In some cases, you&rsquo;ll be required to lie on that specialized plate and the camera is moved across your body to capture X-ray images.</span></p>
          <p><span style="font-weight: 400;">Consult and book an appointment with the top radiologists in Lahore to get your X-ray done.</span></p>','is_active' => '1','parent_id' => '205','created_by' => '1'),
            array('name' => 'General Physician MRI','description' => NULL,'is_active' => '1','parent_id' => '205','created_by' => '1'),
            array('name' => 'Bone Marrow Transplant','description' => NULL,'is_active' => '1','parent_id' => '272','created_by' => '1'),
            array('name' => 'CAR T-cell Therapy','description' => NULL,'is_active' => '1','parent_id' => '272','created_by' => '1'),
            array('name' => 'Thyroid Surgery','description' => NULL,'is_active' => '1','parent_id' => NULL,'created_by' => '1')
          );
          foreach ($aTreatments as $treatment) {
              Treatment::create($treatment);
          }
    }
}
