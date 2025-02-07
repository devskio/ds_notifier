#
# Table structure for table 'tx_dsnotifier_domain_model_notification'
#
CREATE TABLE tx_dsnotifier_domain_model_notification (
    title varchar(255) DEFAULT '' NOT NULL,
    event varchar(255) DEFAULT '' NOT NULL,
    channel varchar(255) DEFAULT '' NOT NULL,
    sites varchar(255) DEFAULT '' NOT NULL,

    subject varchar(255) DEFAULT '' NOT NULL,
    body text,
    markers varchar(255) DEFAULT '' NOT NULL,
    layout varchar(255) DEFAULT '' NOT NULL,
    configuration text,
    email_to varchar(255) DEFAULT '' NOT NULL,
    email_cc varchar(255) DEFAULT '' NOT NULL,
    email_bcc varchar(255) DEFAULT '' NOT NULL,
    slack_channels varchar(255) DEFAULT '' NOT NULL,
);

#
# Table structure for table 'tx_dsnotifier_domain_model_recipient'
#
CREATE TABLE tx_dsnotifier_domain_model_recipient (
    channel varchar(255) DEFAULT '' NOT NULL,
    email varchar(255) DEFAULT '' NOT NULL,
    name varchar(255) DEFAULT '' NOT NULL,
    slack_channel varchar(255) DEFAULT '' NOT NULL,
);

