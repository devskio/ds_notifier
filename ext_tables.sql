#
# Table structure for table 'tx_dsnotifier_domain_model_notification'
#
CREATE TABLE tx_dsnotifier_domain_model_notification (
    title varchar(255) DEFAULT '' NOT NULL,
    event varchar(255) DEFAULT '' NOT NULL,
    channel varchar(255) DEFAULT '' NOT NULL,
    sites varchar(255) DEFAULT '' NOT NULL,
    language_aware smallint(4) DEFAULT 0 NOT NULL,

    subject varchar(255) DEFAULT '' NOT NULL,
    body text,
    markers varchar(255) DEFAULT '' NOT NULL,
    email_to varchar(255) DEFAULT '' NOT NULL,
    email_cc varchar(255) DEFAULT '' NOT NULL,
    email_bcc varchar(255) DEFAULT '' NOT NULL,
);
