#
# Table structure for table 'tx_dsnotifier_domain_model_notification'
#
CREATE TABLE tx_dsnotifier_domain_model_notification (
    title varchar(255) DEFAULT '' NOT NULL,
    event varchar(255) DEFAULT '' NOT NULL,
    channel varchar(255) DEFAULT '' NOT NULL,

    subject varchar(255) DEFAULT '' NOT NULL,
    body text,
);